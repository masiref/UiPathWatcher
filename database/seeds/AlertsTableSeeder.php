<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AlertsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // adding alerts to EVA automated process
        $wap = App\WatchedAutomatedProcess::where('code', 'EVA')->first();
        $wap->alerts()->saveMany([
            new App\Alert([
                'label' => 'Info alert'
            ]),
            new App\Alert([
                'label' => 'Warning alert',
                'level' => 'warning'
            ]),
            new App\Alert([
                'label' => 'Danger alert',
                'level' => 'danger'
            ])
        ]);
        // alert with reviewer
        $user = App\User::where('email', 'jd@uw.com')->first();
        $alert = new App\Alert([
            'label' => 'Info under revision alert',
            'under_revision' => true,
            'revision_started_at' => Carbon::now()
        ]);
        $alert->reviewer()->associate($user);
        $wap->alerts()->save($alert);

        // adding alerts to ROE automated process
        $wap = App\WatchedAutomatedProcess::where('code', 'ROE')->first();
        $wap->alerts()->saveMany([
            new App\Alert([
                'label' => 'Danger alert',
                'level' => 'danger'
            ]),
            new App\Alert([
                'label' => 'Warning alert',
                'level' => 'warning'
            ]),
        ]);
        // alert with reviewer
        $user = App\User::where('email', 'pm@uw.com')->first();
        $alert = new App\Alert([
            'label' => 'Danger under revision alert',
            'level' => 'danger',
            'under_revision' => true,
            'revision_started_at' => Carbon::now()
        ]);
        $alert->reviewer()->associate($user);
        $wap->alerts()->save($alert);
    }
}
