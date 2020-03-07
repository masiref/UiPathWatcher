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
        DB::table('clients')->insert([
            'name' => 'Natixis Payments',
            'code' => 'NPS'
        ]);
        DB::table('clients')->insert([
            'name' => 'Natixis Lease',
            'code' => 'NL'
        ]);
    }
}
