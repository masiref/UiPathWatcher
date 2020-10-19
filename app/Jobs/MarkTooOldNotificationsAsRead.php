<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\User;
use Carbon\Carbon;

class MarkTooOldNotificationsAsRead implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $now = Carbon::now();
        $users = User::all();
        foreach ($users as $user) {
            foreach ($user->unreadNotifications as $notification) {
                $date = Carbon::createFromFormat('Y-m-d H:i:s', $notification->created_at);
                if ($now->diffInMinutes($date) >= 15) {
                    $notification->markAsRead();
                }
            }
        }
    }
}
