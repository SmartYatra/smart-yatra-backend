<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Route;
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
        $routes = Route::all();
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
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }

        // Create the route
        $route = Route::create($request->all());

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
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }

        // Update the route
        $route->update($request->all());

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
        // Delete the route
        $route->delete();

        return $this->sendResponse([], 'Route deleted successfully.');
    }
}
