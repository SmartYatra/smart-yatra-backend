<?php

namespace App\Http\Controllers;

use App\Helpers\GeoHelper;
use App\Http\Controllers\API\BaseController;
use App\Models\Bus;
use Illuminate\Http\Request;

class BusLocationController extends BaseController
{
     /**
     * Update the bus location and geohash.
     */
    public function updateLocation(Request $request, $busId)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $bus = Bus::findOrFail($busId);
        $bus->latitude = $request->latitude;
        $bus->longitude = $request->longitude;
        $bus->geohash = GeoHelper::encodeGeohash($request->latitude, $request->longitude);
        $bus->save();

        return $this->sendResponse(['geohash' => $bus->geohash], 'Location updated successfully');
    }

    /**
     * Retrieve nearby buses based on geohash.
     */
    public function getNearbyBuses(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $geohash = GeoHelper::encodeGeohash($request->latitude, $request->longitude, 5);
        $nearbyBuses = Bus::where('geohash', 'LIKE', $geohash . '%')->get();

        if ($nearbyBuses->isEmpty()) {
            return $this->sendError('No nearby buses found', [], 404);
        }

        return $this->sendResponse($nearbyBuses, 'Nearby buses retrieved successfully');
    }
}
