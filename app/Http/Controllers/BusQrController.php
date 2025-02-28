<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BusQrController extends BaseController
{
    public function getQrData()
    {
        $driver = Auth::user();
        if (!$driver || $driver->type != 'driver')
            return $this->sendError("User Not found or user is not a driver.", [], 404);
        // Fetch the bus record
        $bus = $driver->hasBus;
        if (!$bus)
            return $this->sendError("No bus assigned to the driver", [], 404);

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
