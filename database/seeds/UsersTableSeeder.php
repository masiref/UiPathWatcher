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
            'email' => 'mf@uw.com',
            'password' => Hash::make('louloute'),
            'created_at' => Carbon::now()
        ]);

        DB::table('users')->insert([
            'name' => 'Jack Daniels',
            'email' => 'jd@uw.com',
            'password' => Hash::make('louloute'),
            'created_at' => Carbon::now()
        ]);
    }
}
