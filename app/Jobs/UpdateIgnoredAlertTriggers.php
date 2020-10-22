<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\AlertTrigger;
use Carbon\Carbon;

class UpdateIgnoredAlertTriggers implements ShouldQueue
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

        // retrieve all ignored triggers
        $triggers = AlertTrigger::all()->where('ignored', true)->where('deleted', false)->whereNotNull('ignored_until');
        foreach ($triggers as $trigger) {
            if ($now->gt(Carbon::createFromFormat('Y-m-d H:i:s', $trigger->ignored_until))) {
                $trigger->update([
                    'ignored' => 0,
                    'ignorance_description' => null,
                    'ignored_from' => null,
                    'ignored_until' => null
                ]);
            }
        }

        // retrieve all not ignored triggers with an ignored_from date
        $triggers = AlertTrigger::all()->where('ignored', false)->where('deleted', false)->whereNotNull('ignored_from');
        foreach ($triggers as $trigger) {
            if ($now->gt(Carbon::createFromFormat('Y-m-d H:i:s', $trigger->ignored_from))) {
                $trigger->update([
                    'ignored' => 1
                ]);
            }
        }
    }
}
