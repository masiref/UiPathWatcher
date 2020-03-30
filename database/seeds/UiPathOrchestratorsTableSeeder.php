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
            'elastic_search_url' => 'http://swdcfregb705:9200/',
            'elastic_search_index' => 'kibana_sample_data_flights'
        ]);
        DB::table('ui_path_orchestrators')->insert([
            'name' => 'Natixis Lease',
            'code' => 'NL',
            'url' => 'https://uipath.dev.mycloud.intranatixis.com/',
            'tenant' => 'Default',
            'api_user_username' => 'api_user',
            'api_user_password' => 'apiuser2019!',
            'elastic_search_url' => 'http://swdcfregb705:9200/',
            'elastic_search_index' => 'kibana_sample_data_ecommerce'
        ]);
    }
}
