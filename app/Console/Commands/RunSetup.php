<?php

namespace App\Console\Commands;

use App\Enums\JobStatus;
use App\Models\JobState;
use Ignite\Setup\Jobs\SetupProcess;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'app:setup')]
class RunSetup extends Command
{
    /**
     * The polling interval in seconds.
     *
     * @var int
     */
    protected const POLLING_INTERVAL = 1;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set up application defaults.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->info('Starting setup process...');

        $this->newLine();

        $progressBar = $this->output->createProgressBar(100);

        SetupProcess::dispatch($jobId = uniqid(more_entropy: true));

        $this->info('Setup process started...');

        $this->newLine();

        $progressBar->start();

        // Continuously check the job's progress
        while (true) {
            sleep(static::POLLING_INTERVAL); // Polling interval

            // Retrieve the job's current state
            $jobState = JobState::where('job_id', $jobId)->first();

            if (! $jobState) {
                $this->error('Could not find job state.');

                return static::FAILURE;
            }

            // Update the progress bar
            $progressBar->setProgress($jobState->progress);

            // Check if the job is completed or failed
            if ($jobState->state === JobStatus::COMPLETED->value) {
                $progressBar->finish();

                $this->newLine();

                $this->info("\nSetup completed successfully.");

                break;
            } elseif ($jobState->state === JobStatus::FAILED->value) {
                $progressBar->finish();

                $this->newLine();

                $this->error("\nSetup failed: {$jobState->message}");

                break;
            }
        }

        return static::SUCCESS;
    }
}
