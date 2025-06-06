<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

// * Station levels don't move much, for simplicity let's populate it at the start.
class StationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table("station_level")->insert([
            'level' => 1,
            'minimum_output' => 1300,
            'maximum_output' => 2400
        ]);

        DB::table("station_level")->insert([
            'level' => 2,
            'minimum_output' => 3000,
            'maximum_output' => 20000
        ]);

        DB::table("station_level")->insert([
            'level' => 1,
            'minimum_output' => 50000,
            'maximum_output' => 350000
        ]);
    }
}
