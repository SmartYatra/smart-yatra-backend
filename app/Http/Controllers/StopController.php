<?php

namespace App\Http\Controllers;

use App\Helpers\GeoHelper;
use App\Http\Controllers\API\BaseController;
use App\Models\Stop;
use Illuminate\Http\Request;

class StopController extends BaseController
{
    public function index()
    {
        $stops = Stop::all();
        return response()->json([
            'stops' => $stops,
        ]);
    }

    public function getNearbyStops(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $latitude = $request->latitude;
        $longitude = $request->longitude;

        // Encode location into geohash with precision 5
        $geohash = GeoHelper::encodeGeohash($latitude, $longitude, 5);

        // Fetch nearby stops using geohash prefix
        $nearbyStops = Stop::where('geohash', 'LIKE', $geohash . '%')->get();

        if ($nearbyStops->isEmpty()) {
            return $this->sendResponse(['success' => false, 'message' => 'No nearby stops found'], 400);
        }

        // Calculate distances and append them to results
        $stopsWithDistance = $nearbyStops->map(function ($stop) use ($latitude, $longitude) {
            $distance = $this->haversineDistance($latitude, $longitude, $stop->location_lat, $stop->location_lng);
            $stop->distance = $distance; // Round to 2 decimal places
            return $stop;
        });

        // Sort by distance (nearest first)
        $sortedStops = $stopsWithDistance->sortBy('distance')->values();

        return $this->sendResponse($sortedStops, 'Nearby stops retrieved successfully');
    }


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
}
