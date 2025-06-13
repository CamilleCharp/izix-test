<?php

namespace Database\Seeders;

use App\Models\StationLevel;
use Illuminate\Database\Seeder;


// * Station levels don't move much, for simplicity let's populate it at the start.
class StationLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StationLevel::truncate();

        StationLevel::create([
            'level' => 1,
            'minimum_output' => 1300,
            'maximum_output' => 2400
        ]);

        StationLevel::create([
            'level' => 2,
            'minimum_output' => 3000,
            'maximum_output' => 20000
        ]);

        StationLevel::create([
            'level' => 3,
            'minimum_output' => 50000,
            'maximum_output' => 350000
        ]);
    }
}
