<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(AdminSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(StationLevelSeeder::class);
        $this->call(TenantSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(StationTypeSeeder::class);
        $this->call(StationSeeder::class);
        $this->call(ConnectorTypeSeeder::class);
        $this->call(VehicleTypesSeeder::class);
    }
}
