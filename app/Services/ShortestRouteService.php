<?php

namespace App\Services;

use App\Models\Stop;
use App\Models\Route;
use App\Models\StandardFare;

class ShortestRouteService
{
    public function findShortestRoute(int $startStopId, int $endStopId)
    {
        $graph = $this->buildGraph();
        $shortestPath = $this->dijkstra($graph, $startStopId, $endStopId);

        if (empty($shortestPath)) {
            return [];
        }

        return $this->buildRouteDetails($shortestPath);
    }

    private function buildRouteDetails(array $path)
    {
        $routeDetails = [];
        $currentRoute = null;
        $currentSegment = [];
        $totalDistance = 0;

        for ($i = 0; $i < count($path) - 1; $i++) {
            $stopId = $path[$i];
            $nextStopId = $path[$i + 1];

            $stop = Stop::find($stopId);
            $nextStop = Stop::find($nextStopId);

            $route = $this->findRouteForStops($stopId, $nextStopId);
            $distance = $this->calculateDistance(
                $stop->location_lat,
                $stop->location_lng,
                $nextStop->location_lat,
                $nextStop->location_lng
            );

            if ($route !== $currentRoute) {
                if ($currentRoute !== null) {
                    $routeDetails[] = [
                        'route' => $currentRoute,
                        'segment' => $currentSegment,
                        'distance' => $totalDistance,
                        'fare'=> $this->getFareForDistance($totalDistance)
                    ];
                }
                $currentRoute = $route;
                $currentSegment = [$stop];
                $totalDistance = 0;
            }

            $currentSegment[] = $nextStop;
            $totalDistance += $distance;
        }

        // Add the last segment
        if ($currentRoute !== null) {
            $routeDetails[] = [
                'route' => $currentRoute,
                'segment' => $currentSegment,
                'distance' => $totalDistance,
                'fare'=> $this->getFareForDistance($totalDistance)
            ];
        }

        return $routeDetails;
    }


    private function findRouteForStops(int $startStopId, int $endStopId)
    {
        // Retrieve the route that contains both stops
        $routes = Route::with('stops')->get();
        foreach ($routes as $route) {
            $stops = $route->stops->pluck('id')->toArray();

            if (in_array($startStopId, $stops) && in_array($endStopId, $stops)) {
                return [
                    'id' => $route->id,
                    'name' => $route->name,
                ];
            }
        }

        return null; // Return null if no route is found
    }


    private function buildGraph()
    {
        $graph = [];
        $routes = Route::with('stops')->get();

        foreach ($routes as $route) {
            $stops = $route->stops->sortBy('pivot.order');

            foreach ($stops as $index => $stop) {
                if (!isset($graph[$stop->id])) {
                    $graph[$stop->id] = [];
                }

                if (isset($stops[$index + 1])) {
                    $nextStop = $stops[$index + 1];

                    $distance = $this->calculateDistance(
                        $stop->location_lat,
                        $stop->location_lng,
                        $nextStop->location_lat,
                        $nextStop->location_lng
                    );

                    $graph[$stop->id][$nextStop->id] = $distance;
                    $graph[$nextStop->id][$stop->id] = $distance; // bidirectional
                }
            }
        }

        return $graph;
    }

    private function dijkstra(array $graph, int $start, int $end)
    {
        $distances = [];
        $previous = [];
        $queue = [];

        foreach ($graph as $node => $edges) {
            $distances[$node] = INF;
            $previous[$node] = null;
            $queue[$node] = INF;
        }

        $distances[$start] = 0;
        $queue[$start] = 0;

        while (!empty($queue)) {
            asort($queue);
            $current = array_key_first($queue);
            unset($queue[$current]);

            if ($current == $end) {
                return $this->buildPath($previous, $end);
            }

            foreach ($graph[$current] as $neighbor => $distance) {
                $alt = $distances[$current] + $distance;
                if ($alt < $distances[$neighbor]) {
                    $distances[$neighbor] = $alt;
                    $previous[$neighbor] = $current;
                    $queue[$neighbor] = $alt;
                }
            }
        }

        return [];
    }

    private function buildPath(array $previous, int $end)
    {
        $path = [];
        for ($at = $end; $at !== null; $at = $previous[$at]) {
            array_unshift($path, $at);
        }

        return $path;
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
        // Find the standard fare based on the distance range
        $fare = StandardFare::where('distance_range_start', '<=', $distance)
            ->where('distance_range_end', '>=', $distance)
            ->first();

        // If no fare is found, return a default fare (e.g., 0)
        if (!$fare) {
            return 15;
        }

        return $fare->fare;
    }
}
