<?php

namespace Database\Seeders;

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
            Permissions::START_CHARGING_SESSION,
            Permissions::END_CHARGING_SESSION,
            Permissions::VIEW_VEHICLES,
            Permissions::REGISTER_VEHICLE,
            Permissions::DELETE_VEHICLE,
        );

        $admin = RoleModel::findByName(Roles::ADMIN->value);
        $admin->givePermissionTo(
            Permissions::VIEW_TENANTS,
            Permissions::REGISTER_TENANT,
            Permissions::REGISTER_LOCATION,
            Permissions::REGISTER_CHARGING_STATION,
            Permissions::DELETE_CHARGING_STATION,
            Permissions::FORCE_END_CHARGING_SESSION,
            Permissions::DELETE_EXTERNAL_VEHICLE,
            Permissions::VIEW_EXTERNAL_VEHICLES,
            Permissions::REGISTER_EXTERNAL_VEHICLE,
        );
    }
}
