<?php

namespace App\Services;

use App\Models\Route;
use App\Models\Stop;
use Illuminate\Database\Eloquent\Collection;

class RouteService
{
    public function getAllRoutes(): Collection
    {
        return Route::with('stops')->get();
    }

    public function createRoute(array $data): Route
    {
        $route = Route::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
            'distance' => $data['distance'],
            'duration' => $data['duration'],
        ]);

        $this->attachStops($route, $data['stops']);

        return $route->load('stops');
    }

    public function getRouteById(Route $route): Route
    {
        return $route->load('stops');
    }

    public function updateRoute(Route $route, array $data): Route
    {
        $route->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'status' => $data['status'],
            'distance' => $data['distance'],
            'duration' => $data['duration'],
        ]);

        if (isset($data['stops'])) {
            $this->attachStops($route, $data['stops']);
        }

        return $route->load('stops');
    }

    public function deleteRoute(Route $route): void
    {
        $route->stops()->detach();
        $route->delete();
    }

    private function attachStops(Route $route, array $stops): void
    {
        $route->stops()->detach();
        foreach ($stops as $stop) {
            $stopModel = Stop::firstOrCreate([
                'name' => $stop['name'],
                'location_lat' => $stop['location_lat'],
                'location_lng' => $stop['location_lng'],
            ]);

            $route->stops()->attach($stopModel->id, ['order' => $stop['order']]);
        }
    }
}
