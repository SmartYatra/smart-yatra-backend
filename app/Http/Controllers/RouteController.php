<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Services\RouteService;
use App\Models\Route;
use Illuminate\Http\Request;
use Validator;

class RouteController extends BaseController
{
    protected $routeService;

    public function __construct(RouteService $routeService)
    {
        $this->routeService = $routeService;
    }

    public function index()
    {
        $routes = $this->routeService->getAllRoutes();
        return $this->sendResponse($routes, 'Routes retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required',
            'distance' => 'required|numeric',
            'duration' => 'required|numeric',
            'stops' => 'required|array|min:2',
            'stops.*.location_lat' => 'required|numeric',
            'stops.*.location_lng' => 'required|numeric',
            'stops.*.order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }

        $route = $this->routeService->createRoute($request->all());
        return $this->sendResponse($route, 'Route created successfully.', 201);
    }

    public function show(Route $route)
    {
        $routeData = $this->routeService->getRouteById($route);
        return $this->sendResponse($routeData, 'Route retrieved successfully.');
    }

    public function update(Request $request, Route $route)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required',
            'distance' => 'required|numeric',
            'duration' => 'required|numeric',
            'stops' => 'sometimes|array|min:2',
            'stops.*.location_lat' => 'required|numeric',
            'stops.*.location_lng' => 'required|numeric',
            'stops.*.order' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }

        $route = $this->routeService->updateRoute($route, $request->all());
        return $this->sendResponse($route, 'Route updated successfully.');
    }

    public function destroy(Route $route)
    {
        $this->routeService->deleteRoute($route);
        return $this->sendResponse([], 'Route deleted successfully.');
    }


    public function getRoutesByIds(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'route_ids' => 'required|array',
            'route_ids.*' => 'integer|exists:routes,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }

        // Fetch routes from DB
        $routes = Route::with('stops')->whereIn('id', $request->route_ids)->get();

        return $this->sendResponse($routes,"Fetched rotues successfully.");
    }
}
