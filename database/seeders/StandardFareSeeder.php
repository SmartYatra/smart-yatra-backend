<?php

namespace Database\Seeders;

use App\Models\StandardFare;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StandardFareSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $standardFares = [
            [0, 5, 20],
            [5, 10, 25],
            [10, 15, 30],
            [15, 20, 33],
        ];
        foreach ($standardFares as $standardFare) {
            StandardFare::create([
                'distance_range_start' => $standardFare[0],
                'distance_range_end' => $standardFare[1],
                'fare' => $standardFare[2]
            ]);
        }
    }
}
