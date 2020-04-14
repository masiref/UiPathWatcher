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
            'code' => '89C3R-R'
        ]);
        $client->orchestrator()->associate($orchestrator);
        $client->save();

        $orchestrator = App\UiPathOrchestrator::where('code', 'DEV19')->first();
        $client = new App\Client([
            'name' => 'Migration Team',
            'code' => 'MIG'
        ]);
        $client->orchestrator()->associate($orchestrator);
        $client->save();
    }
}
