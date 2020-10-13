<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(PermissionsTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(ConnectRelationshipsSeeder::class);
        $this->call(UsersTableSeeder::class);
        //$this->call(UiPathOrchestratorsTableSeeder::class);
        //$this->call(ClientsTableSeeder::class);
        //$this->call(WatchedAutomatedProcessesTableSeeder::class);
        //$this->call(AlertsTableSeeder::class);

        Model::reguard();
    }
}
