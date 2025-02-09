<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\PassengerTrip;
use App\Models\Stop;
use App\Models\Trip;
use Illuminate\Http\Request;
use Validator;

class PassengerTripController extends BaseController
{
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
        //TBD: Usethe Haversine Formula in Eloquent (for MySQL)
        $stopId = Stop::where('location_lat',$request->latitude)
                    ->where('location_lng',$request->longitude)
                    ->first()
                    ->id;

        // Find the active trip for the bus
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
            // Handle alighting
            $passengerTrip->update([
                'alighting_time' => now(),
                'alighting_stop_id' => $stopId,
                'fare' => $this->calculateFare($passengerTrip->boarding_stop_id, $stopId),
            ]);

            return response()->json(['success' => true, 'message' => 'Passenger alighted successfully']);
        } else {
            // Handle boarding
            PassengerTrip::create([
                'passenger_id' => $passengerId,
                'trip_id' => $trip->id,
                'boarding_time' => now(),
                'boarding_stop_id' => $stopId,
            ]);

            return response()->json(['success' => true, 'message' => 'Passenger boarded successfully']);
        }
    }

    private function calculateFare($boardingStopId, $alightingStopId)
    {
        
        //  TBD: look up from standard fare table
        return 20.00; // Example fare
    }
}
