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
            'code' => 'NPS',
            'url' => 'https://uipath.dev.mycloud.intranatixis.com/',
            'tenant' => 'Default',
            'api_user_username' => 'api_user',
            'api_user_password' => 'apiuser2019!',
            'kibana_url' => 'http://nps-kibana.bench.mycloud.intranatixis.com/',
            'kibana_index' => 'nps*'
        ]);
        DB::table('ui_path_orchestrators')->insert([
            'name' => 'Natixis Lease',
            'code' => 'NL',
            'url' => 'https://uipath.dev.mycloud.intranatixis.com/',
            'tenant' => 'Default',
            'api_user_username' => 'api_user',
            'api_user_password' => 'apiuser2019!',
            'kibana_url' => 'http://nl.qua.kibana.intranatixis.com/',
            'kibana_index' => 'lease*'
        ]);
    }
}
