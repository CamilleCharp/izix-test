<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tenant::truncate();

        $tenants = [
            ['name' => 'Green Energy Solutions'],
            ['name' => 'EcoCharge Inc.'],
            ['name' => 'Urban EV Charging Co.'],
            ['name' => 'Highway Power Stations'],
            ['name' => 'SmartCharge Networks'],
            ['name' => 'Renewable Charge Systems'],
            ['name' => 'EV Power Grid'],
            ['name' => 'NextGen Charging'],
            ['name' => 'Future Mobility Chargers'],
            ['name' => 'Sustainable Energy Partners'],
        ];

        foreach ($tenants as $tenant) {
            Tenant::create($tenant);
        }
    }
}
