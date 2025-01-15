<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Route;
use App\Models\Stop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class RouteController extends BaseController
{
    /**
     * Display a listing of the routes.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $routes = Route::with('stops')->get();

        // Sort the stops for each route in ascending order of 'order'
        $routes = $routes->map(function ($route) {
            $route = $route->stops->sortBy('pivot.order')->map(function ($stop) {
                return [
                    'id' => $stop->id,
                    'name' => $stop->name,
                    'location_lng' => $stop->location_lng,
                    'location_lat' => $stop->location_lat,
                    'order' => $stop->pivot->order,
                ];
            });
            return $route;
        });

        return $this->sendResponse($routes, 'Routes retrieved successfully.');
    }

    /**
     * Store a newly created route in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required',
            'distance' => 'required|numeric',
            'duration' => 'required|numeric',
            'stops.*.location_lat' => 'required|numeric',
            'stops.*.location_lng' => 'required|numeric',
            'stops.*.order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }

        // Create the route
        $route = Route::create($request->except('stops'));

        // Add stops to the route
        foreach ($request->stops as $stopData) {
            // Create each stop
            $stop = Stop::create($stopData);

            // Attach the stop to the route with the specified order
            $route->stops()->attach($stop->id, ['order' => $stopData['order']]);
        }

        // Load the stops with the pivot data (order)
        $route->load('stops');

        // Sort the stops by 'order' and format the response
        $route = $route->stops->sortBy('pivot.order')->map(function ($stop) {
            return [
                'id' => $stop->id,
                'name' => $stop->name,
                'location_lng' => $stop->location_lng,
                'location_lat' => $stop->location_lat,
                'order' => $stop->pivot->order, // Add the order field from the pivot table
            ];
        });

        return $this->sendResponse($route, 'Route created successfully.', 201);
    }

    /**
     * Display the specified route.
     *
     * @param \App\Models\Route $route
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Route $route)
    {
        // Load the stops with the pivot data (order)
        $route->load('stops');

        // Sort the stops by 'order' and format the response
        $route = $route->stops->sortBy('pivot.order')->map(function ($stop) {
            return [
                'id' => $stop->id,
                'name' => $stop->name,
                'location_lng' => $stop->location_lng,
                'location_lat' => $stop->location_lat,
                'order' => $stop->pivot->order,
            ];
        });

        return $this->sendResponse($route, 'Route retrieved successfully.');
    }

    /**
     * Update the specified route in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Route $route
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Route $route)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required',
            'distance' => 'required|numeric',
            'duration' => 'required|numeric',
            'stops.*.location_lat' => 'required|numeric',
            'stops.*.location_lng' => 'required|numeric',
            'stops.*.order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }

        // Update the route
        $route->update($request->except('stops'));

        // If stops data is provided, handle updating or attaching new stops
        if ($request->has('stops')) {
            foreach ($request->stops as $stopData) {
                // Create each stop if it does not exist or update it
                $stop = Stop::firstOrCreate(
                    ['location_lng' => $stopData['location_lng'], 'location_lat' => $stopData['location_lat']],
                    $stopData
                );
                $route->stops()->syncWithoutDetaching([$stop->id => ['order' => $stopData['order']]]);
            }
        }

        // Load the stops with the pivot data (order)
        $route->load('stops');

        // Sort the stops by 'order' and format the response
        $route->stops = $route->stops->sortBy('pivot.order')->map(function ($stop) {
            return [
                'id' => $stop->id,
                'name' => $stop->name,
                'location_lng' => $stop->location_lng,
                'location_lat' => $stop->location_lat,
                'order' => $stop->pivot->order,
            ];
        });

        return $this->sendResponse($route, 'Route updated successfully.');
    }

    /**
     * Remove the specified route from storage.
     *
     * @param \App\Models\Route $route
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Route $route)
    {
        // Detach all related stops before deleting the route
        $route->stops()->detach();

        // Delete the route
        $route->delete();

        return $this->sendResponse([], 'Route deleted successfully.');
    }
}
