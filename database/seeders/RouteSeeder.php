<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Route;
use App\Models\Stop;

class RouteSeeder extends Seeder
{
    public function run()
    {
        $routes = [
            [
                'name' => 'Lagankhel - Kalanki',
                'description' => 'Main route covering major intersections.',
                'status' => 'active',
                'distance' => 12,
                'duration' => 35,
                'stops' => [
                    ['name' => 'Lagankhel', 'location_lat' => 27.6675, 'location_lng' => 85.3246, 'order' => 1],
                    ['name' => 'Jawalakhel', 'location_lat' => 27.6734, 'location_lng' => 85.3188, 'order' => 2],
                    ['name' => 'Tripureshwor', 'location_lat' => 27.6936, 'location_lng' => 85.3165, 'order' => 3],
                    ['name' => 'Kalanki', 'location_lat' => 27.6939, 'location_lng' => 85.2816, 'order' => 4],
                ],
            ],
            [
                'name' => 'Gongabu - Balkhu',
                'description' => 'Route connecting north and south Kathmandu.',
                'status' => 'active',
                'distance' => 15,
                'duration' => 40,
                'stops' => [
                    ['name' => 'Gongabu', 'location_lat' => 27.7275, 'location_lng' => 85.3165, 'order' => 1],
                    ['name' => 'Balaju', 'location_lat' => 27.7322, 'location_lng' => 85.3065, 'order' => 2],
                    ['name' => 'Kalimati', 'location_lat' => 27.6955, 'location_lng' => 85.3012, 'order' => 3],
                    ['name' => 'Balkhu', 'location_lat' => 27.6782, 'location_lng' => 85.2963, 'order' => 4],
                ],
            ],
            [
                'name' => 'New Bus Park - Koteshwor',
                'description' => 'East-west route.',
                'status' => 'active',
                'distance' => 10,
                'duration' => 30,
                'stops' => [
                    ['name' => 'New Bus Park', 'location_lat' => 27.7342, 'location_lng' => 85.3179, 'order' => 1],
                    ['name' => 'Maharajgunj', 'location_lat' => 27.7414, 'location_lng' => 85.3279, 'order' => 2],
                    ['name' => 'Chabahil', 'location_lat' => 27.7172, 'location_lng' => 85.3451, 'order' => 3],
                    ['name' => 'Koteshwor', 'location_lat' => 27.6788, 'location_lng' => 85.3457, 'order' => 4],
                ],
            ],
        ];

        foreach ($routes as $routeData) {
            $route = Route::create([
                'name' => $routeData['name'],
                'description' => $routeData['description'],
                'status' => $routeData['status'],
                'distance' => $routeData['distance'],
                'duration' => $routeData['duration'],
            ]);

            foreach ($routeData['stops'] as $stopData) {
                $stop = Stop::firstOrCreate([
                    'name' => $stopData['name'],
                    'location_lat' => $stopData['location_lat'],
                    'location_lng' => $stopData['location_lng'],
                ]);
                $route->stops()->attach($stop->id, ['order' => $stopData['order']]);
            }
        }
    }
}
