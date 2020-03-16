<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Crypt;

class UiPathOrchestratorsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ui_path_orchestrators')->insert([
            'name' => 'Natixis Payments',
            'url' => 'https://nps.qua.orchestrator.intranatixis.com',
            'tenant' => 'Default',
            'api_user_username' => 'api_user',
            'api_user_password' => 'louloute',
            'kibana_url' => 'http://nps-kibana.bench.mycloud.intranatixis.com/',
            'kibana_index' => 'nps*'
        ]);
        DB::table('ui_path_orchestrators')->insert([
            'name' => 'Natixis Lease',
            'url' => 'https://nl.qua.orchestrator.intranatixis.com',
            'tenant' => 'Default',
            'api_user_username' => 'api_user',
            'api_user_password' => 'louloute',
            'kibana_url' => 'http://nl.qua.kibana.intranatixis.com/',
            'kibana_index' => 'lease*'
        ]);
    }
}
