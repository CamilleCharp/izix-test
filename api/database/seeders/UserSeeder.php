<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::where('email', 'test@test.te')->delete();

        $admin = User::create([
            'name' => 'Test user',
            'email' => 'test@test.te',
            'password' => Hash::make('test')
        ]);

        $admin->assignRole('user');
    }
}
