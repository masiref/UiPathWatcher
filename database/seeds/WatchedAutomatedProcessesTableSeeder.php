<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class WatchedAutomatedProcessesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // adding watched automated processes to NPS
        $client = App\Client::where('code', 'NPS')->first();
        $client->watchedAutomatedProcesses()->saveMany([
            new App\WatchedAutomatedProcess([
                'code' => 'EVA',
                'name' => 'Filtrage des flux - Application des réponses',
                'operational_handbook_page_url' => 'http://www.google.fr',
                'kibana_dashboard_url' => 'http://www.amazon.fr',
                'additional_information' => 'Critical process!',
                'running_period_monday' => true,
                'running_period_tuesday' => true,
                'running_period_wednesday' => true,
                'running_period_thursday' => true,
                'running_period_friday' => true,
                'running_period_saturday' => false,
                'running_period_sunday' => false,
                'running_period_time_from' => '06:00:00',
                'running_period_time_until' => '19:00:00'
            ]),
            new App\WatchedAutomatedProcess([
                'code' => 'EWZ',
                'name' => 'Contestation porteur',
                'operational_handbook_page_url' => 'http://www.google.fr',
                'kibana_dashboard_url' => 'http://www.amazon.fr',
                'running_period_monday' => true,
                'running_period_tuesday' => true,
                'running_period_wednesday' => true,
                'running_period_thursday' => true,
                'running_period_friday' => true,
                'running_period_saturday' => false,
                'running_period_sunday' => false,
                'running_period_time_from' => '09:00:00',
                'running_period_time_until' => '18:00:00'
            ])
        ]);

        // adding watched automated processes to NL
        $client = App\Client::where('code', 'NL')->first();
        $client->watchedAutomatedProcesses()->saveMany([
            new App\WatchedAutomatedProcess([
                'code' => 'ROE',
                'name' => 'Calcul ROE',
                'operational_handbook_page_url' => 'http://www.google.fr',
                'kibana_dashboard_url' => 'http://www.amazon.fr',
                'additional_information' => 'Critical process!',
                'running_period_monday' => true,
                'running_period_tuesday' => true,
                'running_period_wednesday' => true,
                'running_period_thursday' => true,
                'running_period_friday' => true,
                'running_period_saturday' => false,
                'running_period_sunday' => false,
                'running_period_time_from' => '14:00:00',
                'running_period_time_until' => '19:00:00'
            ])
        ]);
    }
}
