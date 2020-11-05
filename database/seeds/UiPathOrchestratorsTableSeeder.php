<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use DB;

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
            'created_at' => Carbon::now()
        ]);
        DB::table('ui_path_orchestrators')->insert([
            'name' => 'Development-19',
            'code' => 'DEV19',
            'url' => 'https://uipath.dev.mycloud.intranatixis.com/',
            'created_at' => Carbon::now()
        ]);
    }
}
