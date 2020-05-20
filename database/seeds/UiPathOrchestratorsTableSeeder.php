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
            'name' => 'Development-17',
            'code' => 'DEV17',
            'url' => 'https://orchestrator2017.dev.intranatixis.com/',
            'tenant' => 'Default',
            'api_user_username' => 'api_user',
            'api_user_password' => 'apiuser2019!'
        ]);
        DB::table('ui_path_orchestrators')->insert([
            'name' => 'Development-19',
            'code' => 'DEV19',
            'url' => 'https://uipath.dev.mycloud.intranatixis.com/',
            'tenant' => 'Default',
            'api_user_username' => 'api_user',
            'api_user_password' => 'apiuser2019!'
        ]);
    }
}
