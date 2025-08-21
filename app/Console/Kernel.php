<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('cache:preload-area-pages')->everyFiveMinutes();
        // $schedule->command('cast:update-status')->everyThirtyMinutes();
        $schedule->command('cast:update-status')->everyFiveMinutes();
        $schedule->command('cache:home-page')->everyTenMinutes();
        $schedule->command('cache:detail-genre-page')->everyTenMinutes();
        // $schedule->command('cache:home-page')->everyTwoMinutes(); // Test mode
        // $schedule->command('cache:detail-genre-page')->everyTwoMinutes(); // Test mode

        $schedule->command('cast:check-incoming-mail')->everyFiveMinutes();
        $schedule->command('cache:site-detail-page')->everyFiveMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
