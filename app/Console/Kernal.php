<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Example: clean up long DB connections every 10 minutes
        $schedule->command('db:prune-connections')->everyTenMinutes();

        // Example: process queues
        $schedule->command('queue:work --stop-when-empty')->everyMinute();
            // Run Laravel queue worker but restart it frequently to avoid stuck jobs
    $schedule->command('queue:restart')->everyTenMinutes();

    // Clear expired DB sessions and cache
    $schedule->command('session:prune')->daily();
    $schedule->command('cache:prune-stale-tags')->daily();

    // Kill long-running DB connections (custom command)
    $schedule->command('db:kill-long-connections')->everyFiveMinutes();

    // Optimize DB tables occasionally
    $schedule->command('optimize')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
