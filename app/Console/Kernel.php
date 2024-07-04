<?php

namespace App\Console;

use App\Enums\AppMode;
use App\Facades\App;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        if (! App::hasModeEnabled(AppMode::CLI)) {
            return;
        }

        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console/routes.php');
    }
}
