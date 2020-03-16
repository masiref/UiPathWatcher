<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(WatchedAutomatedProcessesTableSeeder::class);
        $this->call(AlertsTableSeeder::class);
        $this->call(UiPathOrchestratorsTableSeeder::class);
    }
}
