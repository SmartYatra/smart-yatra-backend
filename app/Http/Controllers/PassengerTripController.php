<?php

namespace App\Http\Controllers;

use App\Models\Stop;
use Illuminate\Http\Request;
use App\Models\PassengerTrip;
use App\Models\Trip;
use App\Helpers\GeoHelper;
use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\StandardFare;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon as Carbon;

class PassengerTripController extends BaseController
{
    // Haversine formula to calculate the distance between two geographical points
    public function haversineDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371; // Radius of Earth in kilometers

        $latFrom = deg2rad($lat1);
        $lngFrom = deg2rad($lng1);
        $latTo = deg2rad($lat2);
        $lngTo = deg2rad($lng2);

        $latDiff = $latTo - $latFrom;
        $lngDiff = $lngTo - $lngFrom;

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos($latFrom) * cos($latTo) *
            sin($lngDiff / 2) * sin($lngDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; // Returns the distance in kilometers
    }

    // Scan method for boarding or alighting a passenger
    public function scan(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'bus_id' => 'required|exists:buses,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'stop_id' => 'nullable|exists:stops,id'
        ]);
        $passenger = Auth::user();
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $busId = $request->bus_id;

        // Find the active trip for the bus
        $trip = Trip::where('bus_id', $busId)->where('status', 'in_progress')->first();

        if (!$trip) {
            return response()->json(['success' => false, 'message' => 'No active trip found for this bus'], 404);
        }

        if ($request->latitude && $request->longitude) {
            $userGoeHash = GeoHelper::encodeGeohash($request->latitude, $request->longitude);
            //find stops in same geohash
            $stops = Stop::where('geohash', 'LIKE', $userGoeHash . '%')->get();
            $closestStop = null;
            $minDistance = PHP_FLOAT_MAX;

            //if multiple stops choose the closest one using
            foreach ($stops as $stop) {
                $distance = $this->haversineDistance(
                    $request->latitude,
                    $request->longitude,
                    $stop->location_lat,
                    $stop->location_lng
                );

                if ($distance <= 1 && $distance < $minDistance) {
                    $minDistance = $distance;
                    $closestStop = $stop;
                }
            }
            if (!$closestStop) {
                return $this->sendError("Couldn't Determine Stop near the location", 400);
            }
            $stopId = $closestStop->id;

        }
        else if($request->stop_id){
            $stopId = $request->stop_id;
        }
        else{
            return $this->sendError("Either stop id or location is required.", 400);

        }

        // Check if the passenger already has a trip for this bus
        $passengerTrip = PassengerTrip::where('passenger_id', $passenger->id)
            ->where('trip_id', $trip->id)
            ->whereNull('alighting_time')
            ->first();

        if ($passengerTrip) {
            // Handle alighting
            $fare = $this->calculateFare($passengerTrip->boarding_stop_id, $stopId);

            // Deduct from the passenger's wallet
            $deducted = Wallet::deduct($passenger->id, $fare);

            if (!$deducted) {
                return response()->json(['success' => false, 'message' => 'Insufficient balance to alight'], 400);
            }

            $passengerTrip->update([
                'alighting_time' => Carbon::now()->toIso8601String(),
                'alighting_stop_id' => $stopId,
                'fare' => $fare,
            ]);

            return response()->json(['success' => true, 'message' => 'Passenger alighted successfully']);
        } else {
            // Handle boarding
            PassengerTrip::create([
                'passenger_id' => $passenger->id,
                'trip_id' => $trip->id,
                'boarding_time' => Carbon::now()->toIso8601String(),
                'boarding_stop_id' => $stopId,
            ]);

            return response()->json(['success' => true, 'message' => 'Passenger boarded successfully']);
        }
    }


    // Calculate fare (you can customize this calculation logic as needed)
    public function calculateFare($boardingStopId, $alightingStopId)
    {
        // Example: Calculate fare based on the distance between boarding and alighting stops
        $boardingStop = Stop::find($boardingStopId);
        $alightingStop = Stop::find($alightingStopId);

        if (!$boardingStop || !$alightingStop) {
            return 0;
        }

        $distance = $this->haversineDistance(
            $boardingStop->location_lat,
            $boardingStop->location_lng,
            $alightingStop->location_lat,
            $alightingStop->location_lng
        );

        $fare = $this->getFareForDistance($distance);
        return round($fare, 2); // Return the fare rounded to two decimal places
    }

    // Retrieve the fare based on the distance range from the StandardFare table
    private function getFareForDistance($distance)
    {
        // Find the standard fare based on the distance range
        $fare = StandardFare::where('distance_range_start', '<=', $distance)
            ->where('distance_range_end', '>=', $distance)
            ->first();

        // If no fare is found, return a default fare (e.g., 0)
        if (!$fare) {
            return 0;
        }

        return $fare->fare;
    }

    public function status()
    {
        $passenger = Auth::user();
        if (!$passenger) {
            return $this->sendResponse(['success' => false, 'message' => 'Passenger not found'], 400);
        }


        // Find the active trip for the bus
        $trip = PassengerTrip::where('passenger_id', $passenger->id)
        ->whereNotNull('boarding_time')
        ->whereNull('alighting_time')
        ->orderBy('boarding_time','desc')
        ->first();

        if (!$trip) {
            return $this->sendResponse([], "Trip  Not found.");
        }
        $data = [
            'boarding_time' => $trip->boarding_time,
            'alighting_time' => $trip->alighting_time,
            'boarding_stop' => $trip->boardingStop->name ?? null,
            'alighting_stop' => $trip->alightingStop ? $trip->alightingStop->name : null,
            'bus' => $trip->trip ? $trip->trip->bus : null
        ];
        return $this->sendResponse($data, "Trip found.");
    }
}
