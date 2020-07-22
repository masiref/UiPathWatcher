<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Masiré Fofana',
            'email' => 'masire.fofana@natixis.com',
            'password' => Hash::make('uipath'),
            'created_at' => Carbon::now()
        ]);

        DB::table('users')->insert([
            'name' => 'Yohann Gentilini',
            'email' => 'yohann.gentilini@natixis.com',
            'password' => Hash::make('uipath'),
            'created_at' => Carbon::now()
        ]);
    }
}
