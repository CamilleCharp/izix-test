<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Enums\Roles;
use App\Enums\Permissions;

use Spatie\Permission\Models\Role as RoleModel;
use Spatie\Permission\Models\Permission as PermissionModel;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Save users roles
        $users = User::withoutRole(Roles::ADMIN)->get();
        $admins = User::role(Roles::ADMIN)->get();

        PermissionModel::get()->each(function ($permission) {
            $permission->delete();
        });
        
        RoleModel::get()->each(function ($role) {
            $role->delete();
        });

        foreach (Roles::cases() as $role) {
            RoleModel::create(['name' => $role->value]);
        }

        foreach (Permissions::cases() as $permission) {
            PermissionModel::create(['name' => $permission->value]);
        }

        // Assign permissions to roles

        $user = RoleModel::findByName(Roles::USER->value);
        $user->givePermissionTo(
            Permissions::REGISTER_VEHICLE,
            Permissions::VIEW_VEHICLES,
            Permissions::DELETE_VEHICLE,
            Permissions::VIEW_CHARGING_STATIONS_TYPES,
            Permissions::VIEW_CONNECTORS,
            Permissions::START_CHARGING_SESSION,
            Permissions::END_CHARGING_SESSION,
        );

        $admin = RoleModel::findByName(Roles::ADMIN->value);
        $admin->givePermissionTo(
            Permissions::VIEW_TENANTS,
            Permissions::REGISTER_TENANT,
            Permissions::UPDATE_TENANT,
            Permissions::DELETE_TENANT,

            Permissions::REGISTER_LOCATION,
            Permissions::UPDATE_LOCATION,
            Permissions::DELETE_LOCATION,

            Permissions::REGISTER_CHARGING_STATION_TYPE,
            PERMISSIONS::DELETE_CHARGING_STATION_TYPE,

            Permissions::REGISTER_CHARGING_STATION,
            Permissions::UPDATE_CHARGING_STATION,
            Permissions::DELETE_CHARGING_STATION,

            Permissions::REGISTER_CONNECTOR,
            Permissions::DELETE_CONNECTOR,
            Permissions::REGISTER_VEHICLE_TYPE,

            Permissions::UPDATE_VEHICLE_TYPE,
            Permissions::DELETE_VEHICLE_TYPE,
            Permissions::VIEW_VEHICLE_TYPES,

            Permissions::DELETE_EXTERNAL_VEHICLE,
            Permissions::VIEW_EXTERNAL_VEHICLES,
            Permissions::REGISTER_EXTERNAL_VEHICLE,
            Permissions::UPDATE_EXTERNAL_VEHICLE,

            Permissions::FORCE_END_CHARGING_SESSION,
        );

        // Reapply the roles
        foreach($users as $user) {
            $user->assignRole(Roles::USER);
        }

        foreach($admins as $admin) {
            $admin->assignRole(Roles::USER, Roles::ADMIN);
        }
    }
}
