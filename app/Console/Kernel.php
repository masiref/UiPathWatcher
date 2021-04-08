<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\ProcessAlertTriggers;
use App\Jobs\UpdateUiPathRobotsStatuses;
use App\Jobs\MarkTooOldNotificationsAsRead;
use App\Jobs\UpdateIgnoredAlertTriggers;

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
        $schedule->job(new ProcessAlertTriggers)->everyMinute();
        $schedule->job(new UpdateUiPathRobotsStatuses)->everyMinute();
        $schedule->job(new MarkTooOldNotificationsAsRead)->everyMinute();
        $schedule->job(new UpdateIgnoredAlertTriggers)->everyMinute();
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
