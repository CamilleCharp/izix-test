<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name' => 'admin']);

        $registerTenant = Permission::create(['name'=> 'Register a new tenant']);
        $registerLocation = Permission::create(['name'=> 'Register a new location']);
        $registerStation = Permission::create(['name'=> 'Register a charging station']);
        $forceEnd = Permission::create(['name'=> 'Force the end of a charging session']);

        $admin->givePermissionTo($registerTenant, $registerLocation, $registerStation, $forceEnd);

        $user = Role::create(['name'=> 'user']);

        $registerVehicle = Permission::create(['name'=> 'Register a vehicle']);
        $startChargingSession = Permission::create(['name'=> 'Start a charging session']);
        $endChargingSession = Permission::create(['name'=> 'End a charging session']);

        $user ->givePermissionTo($registerVehicle, $startChargingSession, $endChargingSession);
    }
}
