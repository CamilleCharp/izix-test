<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Create the base admin
class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::where('email', 'admin@admin.ad')->delete();

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.ad',
            'password' => Hash::make('admin')
        ]);

        $admin->assignRole('user', 'admin');
    }
}
