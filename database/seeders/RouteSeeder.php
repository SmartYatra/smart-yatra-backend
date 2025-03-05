<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Route;
use App\Models\Stop;
use Illuminate\Support\Facades\DB;

class RouteSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the related tables
        DB::table('route_stop')->truncate(); // Pivot table for the relationship
        Route::truncate(); // Truncate the 'routes' table
        Stop::truncate(); // Truncate the 'stops' table

        // Optional: Re-enable foreign key checks after truncating
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $routes = [
            [
                'name' => 'Lagankhel - Gongabu New Bus Park',
                'description' => 'Route connecting Lagankhel to Gongabu New Bus Park via major intersections.',
                'status' => 'active',
                'distance' => 12.4, // Approximate distance in kilometers
                'duration' => 35, // Approximate duration in minutes
                'stops' => [
                    ['name' => 'Lagankhel', 'location_lat' => 27.6667, 'location_lng' => 85.3200, 'order' => 1],
                    ['name' => 'Kumaripati', 'location_lat' => 27.6678, 'location_lng' => 85.3175, 'order' => 2],
                    ['name' => 'Jawalakhel', 'location_lat' => 27.6730, 'location_lng' => 85.3140, 'order' => 3],
                    ['name' => 'Pulchowk', 'location_lat' => 27.6762, 'location_lng' => 85.3124, 'order' => 4],
                    ['name' => 'Harihar Bhawan', 'location_lat' => 27.6780, 'location_lng' => 85.3128, 'order' => 5],
                    ['name' => 'Kupondole', 'location_lat' => 27.6825, 'location_lng' => 85.3147, 'order' => 6],
                    ['name' => 'Tripureshwor', 'location_lat' => 27.6940, 'location_lng' => 85.3120, 'order' => 7],
                    ['name' => 'NAC', 'location_lat' => 27.7010, 'location_lng' => 85.3160, 'order' => 8],
                    ['name' => 'Jamal', 'location_lat' => 27.7075, 'location_lng' => 85.3155, 'order' => 9],
                    ['name' => 'Lainchaur', 'location_lat' => 27.7130, 'location_lng' => 85.3158, 'order' => 10],
                    ['name' => 'Lazimpat', 'location_lat' => 27.7195, 'location_lng' => 85.3185, 'order' => 11],
                    ['name' => 'Panipokhari', 'location_lat' => 27.7238, 'location_lng' => 85.3203, 'order' => 12],
                    ['name' => 'Rastrapati Bhawan', 'location_lat' => 27.7255, 'location_lng' => 85.3210, 'order' => 13],
                    ['name' => 'Teaching Hospital', 'location_lat' => 27.7280, 'location_lng' => 85.3240, 'order' => 14],
                    ['name' => 'Narayangopal Chowk', 'location_lat' => 27.7350, 'location_lng' => 85.3300, 'order' => 15],
                    ['name' => 'Basundhara', 'location_lat' => 27.7490, 'location_lng' => 85.3350, 'order' => 16],
                    ['name' => 'Samakhushi', 'location_lat' => 27.7410, 'location_lng' => 85.3198, 'order' => 17],
                    ['name' => 'Gongabu New Bus Park', 'location_lat' => 27.7360, 'location_lng' => 85.3110, 'order' => 18],
                ],
            ],

            [
                'name' => 'Lagankhel - Budhanilkantha',
                'description' => 'Route connecting Lagankhel to Budhanilkantha via major intersections.',
                'status' => 'active',
                'distance' => 15.8, // Approximate distance in kilometers
                'duration' => 45, // Approximate duration in minutes
                'stops' => [
                    ['name' => 'Lagankhel', 'location_lat' => 27.6667, 'location_lng' => 85.3200, 'order' => 1],
                    ['name' => 'Kumaripati', 'location_lat' => 27.6678, 'location_lng' => 85.3175, 'order' => 2],
                    ['name' => 'Jawalakhel', 'location_lat' => 27.6730, 'location_lng' => 85.3140, 'order' => 3],
                    ['name' => 'Pulchowk', 'location_lat' => 27.6762, 'location_lng' => 85.3124, 'order' => 4],
                    ['name' => 'Harihar Bhawan', 'location_lat' => 27.6780, 'location_lng' => 85.3128, 'order' => 5],
                    ['name' => 'Kupondole', 'location_lat' => 27.6825, 'location_lng' => 85.3147, 'order' => 6],
                    ['name' => 'Tripureshwor', 'location_lat' => 27.6940, 'location_lng' => 85.3120, 'order' => 7],
                    ['name' => 'NAC', 'location_lat' => 27.7010, 'location_lng' => 85.3160, 'order' => 8],
                    ['name' => 'Jamal', 'location_lat' => 27.7085, 'location_lng' => 85.3168, 'order' => 9],
                    ['name' => 'Lainchaur', 'location_lat' => 27.7140, 'location_lng' => 85.3185, 'order' => 10],
                    ['name' => 'Lazimpat', 'location_lat' => 27.7195, 'location_lng' => 85.3185, 'order' => 11],
                    ['name' => 'Panipokhari', 'location_lat' => 27.7238, 'location_lng' => 85.3203, 'order' => 12],
                    ['name' => 'Rastrapati Bhawan', 'location_lat' => 27.7255, 'location_lng' => 85.3210, 'order' => 13],
                    ['name' => 'Teaching Hospital', 'location_lat' => 27.7280, 'location_lng' => 85.3240, 'order' => 14],
                    ['name' => 'Narayangopal Chowk', 'location_lat' => 27.7350, 'location_lng' => 85.3300, 'order' => 15],
                    ['name' => 'Gangalaal Hospital', 'location_lat' => 27.7415, 'location_lng' => 85.3325, 'order' => 16],
                    ['name' => 'Neuro Hospital', 'location_lat' => 27.7485, 'location_lng' => 85.3352, 'order' => 17],
                    ['name' => 'Gokarna', 'location_lat' => 27.7650, 'location_lng' => 85.3652, 'order' => 18],
                    ['name' => 'Telecom Chowk', 'location_lat' => 27.7705, 'location_lng' => 85.3489, 'order' => 19],
                    ['name' => 'Hattigauda', 'location_lat' => 27.7755, 'location_lng' => 85.3503, 'order' => 20],
                    ['name' => 'Chapali', 'location_lat' => 27.7850, 'location_lng' => 85.3555, 'order' => 21],
                    ['name' => 'Dhekuwa Chowk', 'location_lat' => 27.7900, 'location_lng' => 85.3600, 'order' => 22],
                    ['name' => 'Budhanilkantha', 'location_lat' => 27.7930, 'location_lng' => 85.3630, 'order' => 23],
                ],
            ],
            [
                'name' => 'Godavari - Ratnapark',
                'description' => 'Route connecting Godavari to Ratnapark via major intersections.',
                'status' => 'active',
                'distance' => 18, // Approximate distance in kilometers
                'duration' => 50, // Approximate duration in minutes
                'stops' => [
                    ['name' => 'Godavari', 'location_lat' => 27.5900, 'location_lng' => 85.4000, 'order' => 1],
                    ['name' => 'Taukhel', 'location_lat' => 27.5975, 'location_lng' => 85.3860, 'order' => 2],
                    ['name' => 'Hodigaun', 'location_lat' => 27.6050, 'location_lng' => 85.3765, 'order' => 3],
                    ['name' => 'Badegaun', 'location_lat' => 27.6155, 'location_lng' => 85.3650, 'order' => 4],
                    ['name' => 'Harisiddhi', 'location_lat' => 27.6235, 'location_lng' => 85.3560, 'order' => 5],
                    ['name' => 'Hatiban', 'location_lat' => 27.6350, 'location_lng' => 85.3485, 'order' => 6],
                    ['name' => 'Khumaltar', 'location_lat' => 27.6450, 'location_lng' => 85.3420, 'order' => 7],
                    ['name' => 'Satdobato', 'location_lat' => 27.6530, 'location_lng' => 85.3295, 'order' => 8],
                    ['name' => 'Lagankhel', 'location_lat' => 27.6667, 'location_lng' => 85.3200, 'order' => 9],
                    ['name' => 'Kumaripati', 'location_lat' => 27.6678, 'location_lng' => 85.3175, 'order' => 10],
                    ['name' => 'Jawalakhel', 'location_lat' => 27.6730, 'location_lng' => 85.3140, 'order' => 11],
                    ['name' => 'Pulchowk', 'location_lat' => 27.6762, 'location_lng' => 85.3124, 'order' => 12],
                    ['name' => 'Harihar Bhawan', 'location_lat' => 27.6780, 'location_lng' => 85.3128, 'order' => 13],
                    ['name' => 'Kupondole', 'location_lat' => 27.6825, 'location_lng' => 85.3147, 'order' => 14],
                    ['name' => 'Tripureshwor', 'location_lat' => 27.6940, 'location_lng' => 85.3120, 'order' => 15],
                    ['name' => 'NAC', 'location_lat' => 27.7010, 'location_lng' => 85.3160, 'order' => 16],
                    ['name' => 'Ratnapark', 'location_lat' => 27.7060, 'location_lng' => 85.3160, 'order' => 17],
                ],
            ],
            [
                'name' => 'Lamatar - Ratnapark',
                'description' => 'Route connecting Lamatar to Ratnapark via major intersections.',
                'status' => 'active',
                'distance' => 17, // Approximate distance in kilometers
                'duration' => 50, // Approximate duration in minutes
                'stops' => [
                    ['name' => 'Lamatar', 'location_lat' => 27.6210, 'location_lng' => 85.4230, 'order' => 1],
                    ['name' => 'Dudhin', 'location_lat' => 27.6200, 'location_lng' => 85.4150, 'order' => 2],
                    ['name' => 'Lubhu', 'location_lat' => 27.6180, 'location_lng' => 85.4030, 'order' => 3],
                    ['name' => 'School Chowk', 'location_lat' => 27.6170, 'location_lng' => 85.3950, 'order' => 4],
                    ['name' => 'Sanagaun', 'location_lat' => 27.6150, 'location_lng' => 85.3850, 'order' => 5],
                    ['name' => 'Kamalpokhari', 'location_lat' => 27.6500, 'location_lng' => 85.3450, 'order' => 6],
                    ['name' => 'Imadol Krishna Mandir', 'location_lat' => 27.6550, 'location_lng' => 85.3380, 'order' => 7],
                    ['name' => 'Ratameke Chowk', 'location_lat' => 27.6600, 'location_lng' => 85.3300, 'order' => 8],
                    ['name' => 'KIST Hospital', 'location_lat' => 27.6650, 'location_lng' => 85.3280, 'order' => 9],
                    ['name' => 'Gwarko', 'location_lat' => 27.6675, 'location_lng' => 85.3265, 'order' => 10],
                    ['name' => 'Satdobato', 'location_lat' => 27.6530, 'location_lng' => 85.3295, 'order' => 11],
                    ['name' => 'Lagankhel', 'location_lat' => 27.6667, 'location_lng' => 85.3200, 'order' => 12],
                    ['name' => 'Jawalakhel', 'location_lat' => 27.6730, 'location_lng' => 85.3140, 'order' => 13],
                    ['name' => 'Pulchowk', 'location_lat' => 27.6762, 'location_lng' => 85.3124, 'order' => 14],
                    ['name' => 'Harihar Bhawan', 'location_lat' => 27.6780, 'location_lng' => 85.3128, 'order' => 15],
                    ['name' => 'Kupondole', 'location_lat' => 27.6825, 'location_lng' => 85.3147, 'order' => 16],
                    ['name' => 'Tripureshwor', 'location_lat' => 27.6940, 'location_lng' => 85.3120, 'order' => 17],
                    ['name' => 'NAC', 'location_lat' => 27.7010, 'location_lng' => 85.3160, 'order' => 18],
                    ['name' => 'Jamal', 'location_lat' => 27.7085, 'location_lng' => 85.3168, 'order' => 19],
                    ['name' => 'Lazimpat', 'location_lat' => 27.7175, 'location_lng' => 85.3195, 'order' => 20],
                    ['name' => 'Lainchaur', 'location_lat' => 27.7140, 'location_lng' => 85.3185, 'order' => 21],
                    ['name' => 'Ratnapark', 'location_lat' => 27.7060, 'location_lng' => 85.3160, 'order' => 22],
                ],
            ],
            [
                "name" => "Thanakot - Tribhuvan International Airport",
                "description" => "Route connecting Thanakot to Tribhuvan International Airport via major intersections.",
                "status" => "active",
                "distance" => 18, // Approximate distance in kilometers
                "duration" => 60, // Approximate duration in minutes
                "stops" => [
                    ["name" => "Thanakot", "location_lat"=> 27.6930, "location_lng"=> 85.2550, "order"=> 1],
                    ["name" => "Tribhuvan Park", "location_lat"=> 27.6900, "location_lng"=> 85.2650, "order"=> 2],
                    ["name" => "Checkpost", "location_lat"=> 27.6850, "location_lng"=> 85.2750, "order"=> 3],
                    ["name" => "Satungal", "location_lat"=> 27.6800, "location_lng"=> 85.2850, "order"=> 4],
                    ["name" => "Naikap", "location_lat"=> 27.6750, "location_lng"=> 85.2950, "order"=> 5],
                    ["name" => "Duduadda", "location_lat"=> 27.6700, "location_lng"=> 85.3050, "order"=> 6],
                    ["name" => "Kalanki", "location_lat"=> 27.6650, "location_lng"=> 85.3150, "order"=> 7],
                    ["name" => "Kalanki Mandir", "location_lat"=> 27.6620, "location_lng"=> 85.3200, "order"=> 8],
                    ["name" => "Ravibhawan", "location_lat"=> 27.6600, "location_lng"=> 85.3250, "order"=> 9],
                    ["name" => "Soltimod", "location_lat"=> 27.6550, "location_lng"=> 85.3300, "order"=> 10],
                    ["name" => "Kalimati", "location_lat"=> 27.6500, "location_lng"=> 85.3350, "order"=> 11],
                    ["name" => "Teku", "location_lat"=> 27.6450, "location_lng"=> 85.3400, "order"=> 12],
                    ["name" => "Vipareshwar", "location_lat"=> 27.6400, "location_lng"=> 85.3450, "order"=> 13],
                    ["name" => "N.A.B", "location_lat"=> 27.6350, "location_lng"=> 85.3500, "order"=> 14],
                    ["name" => "Ratnapark", "location_lat"=> 27.7100, "location_lng"=> 85.3150, "order"=> 15],
                    ["name" => "Singhadarbar", "location_lat"=> 27.7200, "location_lng"=> 85.3200, "order"=> 16],
                    ["name" => "Maitighar", "location_lat"=> 27.7250, "location_lng"=> 85.3250, "order"=> 17],
                    ["name" => "Bijuli Bazar", "location_lat"=> 27.7300, "location_lng"=> 85.3300, "order"=> 18],
                    ["name" => "Naya Baneshwor", "location_lat"=> 27.7350, "location_lng"=> 85.3350, "order"=> 19],
                    ["name" => "Tinkune", "location_lat"=> 27.7400, "location_lng"=> 85.3400, "order"=> 20],
                    ["name" => "Sinamangal", "location_lat"=> 27.7450, "location_lng"=> 85.3450, "order"=> 21],
                    ["name" => "Tribhuvan International Airport", "location_lat"=> 27.7000, "location_lng"=> 85.3600, "order"=> 22]
                ]
            ],
            [
                "name" => "Thanakot - Budhanilkantha",
                "description" => "Route connecting Thanakot to Budhanilkantha via major intersections.",
                "status" => "active",
                "distance" => 23, // Approximate distance in kilometers
                "duration" => 65, // Approximate duration in minutes
                "stops" => [
                    ["name" => "Thanakot", "location_lat" => 27.6930, "location_lng" => 85.2550, "order" => 1],
                    ["name" => "Tribhuvan Park", "location_lat" => 27.6900, "location_lng" => 85.2650, "order" => 2],
                    ["name" => "Checkpost", "location_lat" => 27.6850, "location_lng" => 85.2750, "order" => 3],
                    ["name" => "Satungal", "location_lat" => 27.6800, "location_lng" => 85.2850, "order" => 4],
                    ["name" => "Naikap", "location_lat" => 27.6750, "location_lng" => 85.2950, "order" => 5],
                    ["name" => "Kalanki", "location_lat" => 27.6650, "location_lng" => 85.3150, "order" => 6],
                    ["name" => "Bafal", "location_lat" => 27.6620, "location_lng" => 85.3200, "order" => 7],
                    ["name" => "Sitapaila", "location_lat" => 27.6600, "location_lng" => 85.3250, "order" => 8],
                    ["name" => "Swayambhu", "location_lat" => 27.6550, "location_lng" => 85.3300, "order" => 9],
                    ["name" => "Baneshwor", "location_lat" => 27.6500, "location_lng" => 85.3350, "order" => 10],
                    ["name" => "Balaju", "location_lat" => 27.6450, "location_lng" => 85.3400, "order" => 11],
                    ["name" => "Gongabu Buspark", "location_lat" => 27.6400, "location_lng" => 85.3450, "order" => 12],
                    ["name" => "Samakhusi", "location_lat" => 27.6350, "location_lng" => 85.3500, "order" => 13],
                    ["name" => "Basundhara", "location_lat" => 27.6300, "location_lng" => 85.3550, "order" => 14],
                    ["name" => "Narayan Gopal Chowk", "location_lat" => 27.6250, "location_lng" => 85.3600, "order" => 15],
                    ["name" => "Gangalal Hospital", "location_lat" => 27.6200, "location_lng" => 85.3650, "order" => 16],
                    ["name" => "Neuro Hospital", "location_lat" => 27.6150, "location_lng" => 85.3700, "order" => 17],
                    ["name" => "Golfutar", "location_lat" => 27.6100, "location_lng" => 85.3750, "order" => 18],
                    ["name" => "Hattigauda", "location_lat" => 27.6050, "location_lng" => 85.3800, "order" => 19],
                    ["name" => "Dheba Chowk", "location_lat" => 27.6000, "location_lng" => 85.3850, "order" => 20],
                    ["name" => "Budhanilkantha", "location_lat" => 27.5950, "location_lng" => 85.3900, "order" => 21]
                ]
                ],[
                    "name" => "Lele to Jamal",
                    "description" => "Route connecting Lele to Jamal via major intersections.",
                    "status" => "active",
                    "distance" => 18.3, // Approximate distance in kilometers
                    "duration" => 60, // Approximate duration in minutes
                    "stops" => [
                        ["name" => "Tikabhairab", "location_lat" => 27.5910, "location_lng" => 85.4000, "order" => 1],
                        ["name" => "Takhail", "location_lat" => 27.5930, "location_lng" => 85.4100, "order" => 2],
                        ["name" => "Pyanggao", "location_lat" => 27.5950, "location_lng" => 85.4200, "order" => 3],
                        ["name" => "Thecho", "location_lat" => 27.5970, "location_lng" => 85.4300, "order" => 4],
                        ["name" => "Sunakothi", "location_lat" => 27.5990, "location_lng" => 85.4400, "order" => 5],
                        ["name" => "Hokahiti", "location_lat" => 27.6010, "location_lng" => 85.4500, "order" => 6],
                        ["name" => "Khumbutar", "location_lat" => 27.6030, "location_lng" => 85.4600, "order" => 7],
                        ["name" => "Chapagaun Dobato", "location_lat" => 27.6050, "location_lng" => 85.4700, "order" => 8],
                        ["name" => "Lagankhel", "location_lat" => 27.6065, "location_lng" => 85.4750, "order" => 9],
                        ["name" => "Kamari Pati", "location_lat" => 27.6070, "location_lng" => 85.4800, "order" => 10],
                        ["name" => "Jawalakhel", "location_lat" => 27.6080, "location_lng" => 85.4850, "order" => 11],
                        ["name" => "Pulchowk", "location_lat" => 27.6090, "location_lng" => 85.4900, "order" => 12],
                        ["name" => "Harihar Bhawan", "location_lat" => 27.6110, "location_lng" => 85.4950, "order" => 13],
                        ["name" => "Kupondole", "location_lat" => 27.6130, "location_lng" => 85.5000, "order" => 14],
                        ["name" => "Tripureshwor", "location_lat" => 27.6150, "location_lng" => 85.5100, "order" => 15],
                        ["name" => "NAC", "location_lat" => 27.6170, "location_lng" => 85.5200, "order" => 16],
                        ["name" => "Jamal", "location_lat" => 27.6190, "location_lng" => 85.5300, "order" => 17]
                    ]
                ]

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
                ],
            [
                'location_lat'=> $stopData['location_lat'],
                'location_lng'=> $stopData['location_lng']
            ]);
                $route->stops()->attach($stop->id, ['order' => $stopData['order']]);
            }
        }
    }
}
