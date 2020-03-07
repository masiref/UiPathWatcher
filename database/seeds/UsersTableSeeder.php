<?php

use Illuminate\Database\Seeder;

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
            'name' => 'Philip Morris',
            'email' => 'pm@uw.com',
            'password' => Hash::make('louloute'),
        ]);

        DB::table('users')->insert([
            'name' => 'Jack Daniels',
            'email' => 'jd@uw.com',
            'password' => Hash::make('louloute'),
        ]);
    }
}
