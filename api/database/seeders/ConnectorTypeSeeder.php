<?php

namespace Database\Seeders;

use App\Enums\Current;
use App\Models\ConnectorType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConnectorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('connector_types')->truncate();

        ConnectorType::create([
            'name' => 'SAE J1772 (120V)',
            'max_voltage' => 120,
            'max_current' => 16,
            'max_watts' => 1920,
            'current_type' => Current::AC->value
        ]);

        ConnectorType::create([
            'name' => 'SAE J1772 (208/240V)',
            'max_voltage' => 240,
            'max_current' => 80,
            'max_watts' => 19200,
            'current_type' => Current::AC->value
        ]);



        ConnectorType::create([
            'name' => 'Mennekes (Single Phase)',
            'max_voltage' => 230,
            'max_current' => 32,
            'max_watts' => 7600,
            'current_type' => Current::AC->value
        ]);

        ConnectorType::create([
            'name' => 'Mennekes (Three Phase)',
            'max_voltage' => 400,
            'max_current' => 32,
            'max_watts' => 22000,
            'current_type' => Current::AC->value
        ]);

        ConnectorType::create([
            'name' => 'CCS 1',
            'max_voltage' => 1000,
            'max_current' => 500,
            'max_watts' => 360000,
            'current_type' => Current::DC->value
        ]);

        ConnectorType::create([
            'name' => 'CCS 2',
            'max_voltage' => 1000,
            'max_current' => 500,
            'max_watts' => 360000,
            'current_type' => Current::DC->value
        ]);

        ConnectorType::create([
            'name' => 'CHAdeMO',
            'max_voltage' => 400,
            'max_current' => 400,
            'max_watts' => 400000,
            'current_type' => Current::DC->value
        ]);

        ConnectorType::create([
            'name' => 'GB/T (AC)',
            'max_voltage' => 250,
            'max_current' => 32,
            'max_watts' => 7400,
            'current_type' => Current::AC->value
        ]);

        ConnectorType::create([
            'name' => 'GB/T (DC)',
            'max_voltage' => 440,
            'max_current' => 250,
            'max_watts' => 237500,
            'current_type' => Current::DC->value
        ]);
    }
}
