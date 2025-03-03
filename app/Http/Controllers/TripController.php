<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TripController extends BaseController
{
    public function startTrip(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'route_id' => 'required|exists:routes,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $driver = Auth::user();
        if (!$driver || $driver->type != 'driver') {
            return response()->json(['success' => false, 'message' => 'Driver not found'], 404);
        }

        if (!$driver->hasBus) {
            return response()->json(['success' => false, 'message' => 'Driver is not assigned to any bus'], 400);
        }

        $busId = $driver->hasBus->id;

        // Check if a trip is already in progress for this bus
        $existingTrip = Trip::where('bus_id', $busId)->where('status', 'in_progress')->first();
        if ($existingTrip) {
            return response()->json(['success' => false, 'message' => 'A trip is already in progress for this bus'], 400);
        }

        // Start a new trip
        $trip = Trip::create([
            'bus_id' => $busId,
            'route_id' => $request->route_id,
            'start_time' => Carbon::now()->toIso8601String(),
            'status' => 'in_progress',
            'current_passenger_count' => 0,
            'total_passenger_count' => 0,
            'total_fare_collected' => 0,
            'distance_traveled' => 0
        ]);


        return response()->json(['success' => true, 'message' => 'Trip started successfully', 'trip' => $trip]);
    }

    public function endTrip()
    {

        $driver = Auth::user();
        if (!$driver) {
            return response()->json(['success' => false, 'message' => 'Driver not found'], 404);
        }

        if (!$driver->hasBus) {
            return response()->json(['success' => false, 'message' => 'Driver is not assigned to any bus'], 400);
        }

        $busId = $driver->hasBus->id;

        // Find the active trip for the bus
        $trip = Trip::where('bus_id', $busId)->where('status', 'in_progress')->first();

        if (!$trip) {
            return response()->json(['success' => false, 'message' => 'No active trip found for this bus'], 404);
        }

        // End the trip
        $trip->update([
            'end_time' => Carbon::now()->toIso8601String(),
            'status' => 'completed',
        ]);

        return response()->json(['success' => true, 'message' => 'Trip ended successfully']);
    }
    public function tripStatus()
    {
        $driver = Auth::user();
        if (!$driver) {
            return response()->json(['success' => false, 'message' => 'Driver not found'], 404);
        }

        if (!$driver->hasBus) {
            return response()->json(['success' => false, 'message' => 'Driver is not assigned to any bus'], 400);
        }

        $busId = $driver->hasBus->id;

        // Find the active trip for the bus
        $trip = Trip::where('bus_id', $busId)->where('status', 'in_progress')->first();

        if (!$trip) {
            return $this->sendResponse([], "Trip  Not found.");
        }

        $trip->start_time = $trip->start_time ? Carbon::parse($trip->start_time)->toIso8601String() : null;
        $trip->end_time = $trip->end_time ? Carbon::parse($trip->end_time)->toIso8601String() : null;
        return $this->sendResponse($trip, "Trip found.");
    }
}
