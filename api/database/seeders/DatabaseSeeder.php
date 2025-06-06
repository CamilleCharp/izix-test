<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ConnectorTypeSeeder::class);
        $this->call(StationLevelSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
    }
}
