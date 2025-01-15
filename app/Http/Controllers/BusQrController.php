<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BusQrController extends BaseController
{
    public function getQrData($busId)
    {
        // Fetch the bus record
        $bus = Bus::find($busId);

        // Check if the bus exists
        if (!$bus) {
            return response()->json([
                'success' => false,
                'message' => 'Bus not found.'
            ], 404);
        }

        // Generate QR data
        $qrData = [
            'bus_id' => $bus->id,
            'route_id' => $bus->route_id,
            'bus_number' => $bus->bus_number,
            'timestamp' => now()->timestamp, // Add timestamp for security
            'auth_token' => encrypt($bus->id . '|' . now()->timestamp) // Encrypt for additional security
        ];

        return response()->json([
            'success' => true,
            'data' => $qrData,
            'message' => 'QR data retrieved successfully.'
        ], 200);
    }
}
