<?php

namespace App\Console\Commands;

use App\Clients\AWS\Client as AWS;
use Aws\Exception\AwsException;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Env;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'db:backup')]
class BackupDb extends Command
{
    /**
     * The default path to the backup script.
     */
    public const SCIPT_PATH = 'bin/backup.sh';

    /**
     * The default timeout for the backup script.
     */
    public const TIME_OUT = 3600;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--script-path=} {--upload-to-s3}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a SQL dump of the database, archive it and save it to the storage area'; // phpcs:ignore

    /**
     * The path to the backup file.
     *
     * @var string
     */
    protected $backupFilePath;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $scriptPath = $this->option('script-path') ?? base_path(self::SCIPT_PATH);

        if (! file_exists($scriptPath)) {
            $this->error('The backup script does not exist at the specified path: ' . $scriptPath); // phpcs:ignore

            return Command::FAILURE;
        }

        // Execute Backup Script
        $process = new Process([
            '/bin/bash',
            $scriptPath,
            $this->getBackupStoragePath(),
        ]);

        $process->setTimeout(self::TIME_OUT);

        try {
            $process->mustRun(function ($type, $buffer) {
                $this->info($buffer);
            });

            $output = $process->getOutput();

            // Extract the backup file path from the script output
            preg_match('/Backup created: (.*)/', $output, $matches);

            if (! blank($matches[1])) {
                $this->backupFilePath = trim($matches[1]);
            } else {
                $this->error('Failed to determine the backup file path.');
            }

            if (
                $this->backupFilePath
                && file_exists($this->backupFilePath)
                && $this->option('upload-to-s3')
            ) {
                $this->uploadToS3(
                    $this->backupFilePath,
                    basename($this->backupFilePath)
                );
            }
        } catch (ProcessFailedException $exception) {
            $this->error('The backup process failed: ' . $exception->getMessage());

            return Command::FAILURE;
        } catch (AwsException $exception) {
            $this->error('Failed to upload the backup to S3: ' . $exception->getMessage()); // phpcs:ignore

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Get the path to the backup storage area.
     *
     * @param string|null $file
     *
     * @return string
     */
    public function getBackupStoragePath(?string $file = ''): string
    {
        return storage_path('backups') . ($file ? \DIRECTORY_SEPARATOR . $file : '');
    }

    /**
     * Upload the backup to S3.
     *
     * @param string $filePath
     * @param string $fileName
     *
     * @throws Exception
     */
    protected function uploadToS3(string $filePath, string $fileName): void
    {
        $bucket = Env::get('AWS_BUCKET');

        if (! file_exists($filePath) || filesize($filePath) === 0) {
            throw new Exception("Valid backup file does not exist: {$filePath}");
        }

        $this->info("Uploading {$fileName} to S3 bucket {$bucket}...");

        try {
            $result = $this->laravel // @phpstan-ignore-line
                ->make(AWS::class)
                ->createClient('s3')
                ->putObject([
                    'Bucket' => $bucket,
                    'Key' => "backups/{$fileName}",
                    'SourceFile' => $filePath,
                ]);

            $this->info('Upload successful: ' . $result['ObjectURL']);

            unlink($filePath);
        } catch (AwsException $exception) {
            $this->error('Failed to upload the backup to S3: ' . $exception->getMessage()); // phpcs:ignore
            // Optional: Implement retry logic here
        }
    }
}
