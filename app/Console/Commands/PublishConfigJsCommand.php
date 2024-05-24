<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'config:publish')]
class PublishConfigJsCommand extends Command
{
    /**
     * Name of the config file.
     *
     * @var string
     */
    public const CONFIG_FILE = 'items.json';

    /**
     * Mode for directory creation.
     *
     * @var int
     */
    public const MAKE_DIR_MODE = 0777;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish application configuration to json file for usage with JavaScript.';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected Filesystem $files;

    /**
     * Path to where config 'items' file should be located.
     *
     * @var string
     */
    protected $configPath;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
        $this->configPath = resource_path('js/config');
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $this->publishConfigJs();

        $this->info(sprintf(
            'Config items published to json file [%s].',
            self::CONFIG_FILE
        ));

        return 0;
    }

    /**
     * Publish application configuration to json file for usage with JavaScript.
     *
     * @return void
     */
    protected function publishConfigJs(): void
    {
        if (! $this->files->isDirectory($this->configPath)) {
            $this->files->makeDirectory(
                $this->configPath,
                self::MAKE_DIR_MODE,
                true
            );
        }

        $configItems = $this->configPath . \DIRECTORY_SEPARATOR . self::CONFIG_FILE;

        if (! $this->files->exists($configItems)) {
            \touch($configItems);
        }

        $this->files->put(
            $configItems,
            json_encode(config()->all())
        );
    }
}
