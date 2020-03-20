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
        $orchestrator = App\UiPathOrchestrator::where('code', 'NPS')->first();
        $client = new App\Client([
            'name' => 'Natixis Payments',
            'code' => 'NPS'
        ]);
        $client->orchestrator()->associate($orchestrator);
        $client->save();

        $orchestrator = App\UiPathOrchestrator::where('code', 'NL')->first();
        $client = new App\Client([
            'name' => 'Natixis Lease',
            'code' => 'NL'
        ]);
        $client->orchestrator()->associate($orchestrator);
        $client->save();
    }
}
