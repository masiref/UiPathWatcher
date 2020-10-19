<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ProcessAlertTriggers;
use App\Jobs\UpdateUiPathRobotsStatuses;
use App\Jobs\MarkTooOldNotificationsAsRead;

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
        // local
        $schedule->job(new ProcessAlertTriggers)->everyMinute()->environments(['local']);
        $schedule->job(new UpdateUiPathRobotsStatuses)->everyMinute()->environments(['local']);
        $schedule->job(new MarkTooOldNotificationsAsRead)->everyMinute()->environments(['local']);

        // staging & production
        $schedule->job(new ProcessAlertTriggers)->everyFiveMinutes()->environments(['staging', 'production']);
        $schedule->job(new UpdateUiPathRobotsStatuses)->everyFifteenMinutes()->environments(['staging', 'production']);
        $schedule->job(new MarkTooOldNotificationsAsRead)->everyFifteenMinutes()->environments(['staging', 'production']);
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
