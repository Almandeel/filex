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
        $this->call(LaratrustSeeder::class);
        $this->call(GroupsAccountsSeeder::class);
        // $this->call(SuperAdminSeeder::class);
        // $this->call(AppSeeder::class);
    }
}