<?php

namespace App\Services;

use App\Models\Stop;
use App\Models\Route;
use App\Models\StandardFare;

class PossibleRoutesService
{
    public function getAllRoutes(int $startStopId, int $endStopId)
    {
        $routes = Route::with('stops')->get();
        $routeDetails = [];

        foreach ($routes as $route) {
            $stops = $route->stops->pluck('id')->toArray();

            if (!in_array($startStopId, $stops) || !in_array($endStopId, $stops)) {
                continue;
            }

            $startIndex = array_search($startStopId, $stops);
            $endIndex = array_search($endStopId, $stops);

            if ($startIndex === false || $endIndex === false || $startIndex >= $endIndex) {
                continue;
            }

            $segment = array_slice($route->stops->toArray(), $startIndex, $endIndex - $startIndex + 1);
            $totalDistance = 0;

            for ($i = 0; $i < count($segment) - 1; $i++) {
                $totalDistance += $this->calculateDistance(
                    $segment[$i]['location_lat'],
                    $segment[$i]['location_lng'],
                    $segment[$i + 1]['location_lat'],
                    $segment[$i + 1]['location_lng']
                );
            }

            $routeDetails[] = [
                'route' => [
                    'id' => $route->id,
                    'name' => $route->name,
                ],
                'segment' => $segment,
                'distance' => $totalDistance,
                'fare' => $this->getFareForDistance($totalDistance),
            ];
        }

        return $routeDetails;
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // KM

        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        $dLat = $lat2 - $lat1;
        $dLng = $lng2 - $lng1;

        $a = sin($dLat / 2) * sin($dLat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Distance in km
    }

    private function getFareForDistance($distance)
    {
        $fare = StandardFare::where('distance_range_start', '<=', $distance)
            ->where('distance_range_end', '>=', $distance)
            ->first();

        return $fare ? $fare->fare : 15;
    }
}
