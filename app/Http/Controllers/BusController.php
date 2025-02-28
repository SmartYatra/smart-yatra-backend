<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BusController extends BaseController
{
    /**
     * Display a listing of the buses.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $buses = Bus::all();
        return $this->sendResponse($buses, 'Buses retrieved successfully.');
    }

    /**
     * Store a newly created bus in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'bus_number' => 'required|string|max:255',
            'route_id' => 'required|exists:routes,id', // Ensuring route exists
            'model' => 'required|string',
            'capacity' => 'required|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }
        $user = Auth::user();
        if ($user->type != 'driver')
            return $this->sendError('Validation Error', "Driver not found or the user is not a driver.", 400);
        // Create the bus record
        $bus = Bus::create([
            'bus_number' => $request->bus_number,
            'route_id' => $request->route_id,
            'status' => $request->status,
            'model' => $request->model,
            'capacity' => $request->capacity,
            'driver_id' => $user->id,
        ]);

        return $this->sendResponse($bus, 'Bus created successfully.');
    }

    /**
     * Display the specified bus.
     *
     * @param \App\Models\Bus $bus
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Bus $bus)
    {
        return $this->sendResponse($bus, 'Bus retrieved successfully.');
    }

    /**
     * Update the specified bus in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Bus $bus
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Bus $bus)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'bus_number' => 'required|string|max:255',
            'route_id' => 'required|exists:routes,id',
            'status' => 'required|in:active,inactive',
            'model' => 'required|string',
            'capacity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error', $validator->errors(), 400);
        }

        // Update the bus record
        $bus->update([
            'bus_number' => $request->bus_number,
            'route_id' => $request->route_id,
            'status' => $request->status,
            'model' => $request->model,
            'capacity' => $request->capacity,
        ]);

        return $this->sendResponse($bus, 'Bus updated successfully.');
    }

    /**
     * Remove the specified bus from storage.
     *
     * @param \App\Models\Bus $bus
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Bus $bus)
    {
        // Delete the bus
        $bus->delete();

        return $this->sendResponse([], 'Bus deleted successfully.');
    }

    public function getForDriver()
    {
        $driver = Auth::user();

        if (!$driver || $driver->type !== 'driver') {
            return $this->sendError('Bad Request', "User is not a driver", 402);
        }
        $bus = $driver->hasBus;

        if ($bus->isEmpty()) {
            return $this->sendError('Bus Not Found', [], 404);
        }

        return $this->sendResponse($bus, "Bus retrieved successfully");
    }
}
