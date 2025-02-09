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
            'passenger_id' => 'required|exists:users,id',
            'bus_id' => 'required|exists:buses,id',
            'latitude'=> 'required|numeric',
            'longitude'=> 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        $passengerId = $request->passenger_id;
        $busId = $request->bus_id;
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        // Geohash the passenger's current location
        $geohash = GeoHelper::encodeGeohash($latitude, $longitude, 7); // You can use a higher precision here

        // Retrieve nearby stops with a matching geohash prefix
        $nearbyStops = Stop::where('geohash', 'like', substr($geohash, 0, 5) . '%')->get(); // 5 characters of the geohash

        if ($nearbyStops->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No nearby stops found'], 404);
        }

        // Initialize variables for finding the nearest stop
        $nearestStop = null;
        $minDistance = INF; // Initialize with a large number

        // Iterate over nearby stops to find the one closest to the passenger
        foreach ($nearbyStops as $stop) {
            $distance = $this->haversineDistance($latitude, $longitude, $stop->location_lat, $stop->location_lng);

            if ($distance < $minDistance) {
                $minDistance = $distance;
                $nearestStop = $stop;
            }
        }

        // If no nearest stop found (shouldn't happen with valid data), return an error
        if (!$nearestStop) {
            return response()->json(['success' => false, 'message' => 'Could not determine the nearest stop'], 500);
        }

        // Get the active trip for the bus
        $trip = Trip::where('bus_id', $busId)->where('status', 'in_progress')->first();

        if (!$trip) {
            return response()->json(['success' => false, 'message' => 'No active trip found for this bus'], 404);
        }

        // Check if the passenger already has a trip for this bus
        $passengerTrip = PassengerTrip::where('passenger_id', $passengerId)
            ->where('trip_id', $trip->id)
            ->whereNull('alighting_time')
            ->first();

        if ($passengerTrip) {
            // Handle alighting - update alighting details
            $passengerTrip->update([
                'alighting_time' => now(),
                'alighting_stop_id' => $nearestStop->id,
                'fare' => $this->calculateFare($passengerTrip->boarding_stop_id, $nearestStop->id),
            ]);

            return response()->json(['success' => true, 'message' => 'Passenger alighted successfully']);
        } else {
            // Handle boarding - create new passenger trip entry
            PassengerTrip::create([
                'passenger_id' => $passengerId,
                'trip_id' => $trip->id,
                'boarding_time' => now(),
                'boarding_stop_id' => $nearestStop->id,
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
            $boardingStop->location_lat, $boardingStop->location_lng,
            $alightingStop->location_lat, $alightingStop->location_lng
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
}
