<?php

use Illuminate\Database\Seeder;

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
                'additional_information' => 'Critical process!'
            ]),
            new App\WatchedAutomatedProcess([
                'code' => 'EWZ',
                'name' => 'Contestation porteur',
                'operational_handbook_page_url' => 'http://www.google.fr',
                'kibana_dashboard_url' => 'http://www.amazon.fr'
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
                'additional_information' => 'Critical process!'
            ])
        ]);
    }
}
