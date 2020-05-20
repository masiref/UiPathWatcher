<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orchestrator = App\UiPathOrchestrator::where('code', 'DEV17')->first();
        $client = new App\Client([
            'name' => 'Robotics Team',
            'code' => '89C3R-R',
            'elastic_search_url' => 'http://swdcfregb705.cib.net:9200/',
            'elastic_search_index' => 'kibana_sample_data_ecommerce'
        ]);
        $client->orchestrator()->associate($orchestrator);
        $client->save();

        $orchestrator = App\UiPathOrchestrator::where('code', 'DEV19')->first();
        $client = new App\Client([
            'name' => 'Migration Team',
            'code' => 'MIG',
            'elastic_search_url' => 'http://swdcfregb705.cib.net:9200/',
            'elastic_search_index' => 'kibana_sample_data_flights'
        ]);
        $client->orchestrator()->associate($orchestrator);
        $client->save();
    }
}
