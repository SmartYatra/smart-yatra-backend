<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\StandardFare;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StandardFareController extends BaseController
{
    // Get all standard fares
    public function index()
    {
        $fares = StandardFare::all();
        return response()->json(['success' => true, 'data' => $fares], 200);
    }

    // Create a new standard fare
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'distance_range_start' => 'required|numeric',
            'distance_range_end' => 'required|numeric|gt:distance_range_start',
            'fare' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }
        // Validate that no other fare exists in the same range
        $overlap = StandardFare::where('distance_range_start', '<', $request->distance_range_end)
            ->where('distance_range_end', '>', $request->distance_range_start)
            ->exists();

        if ($overlap) {
            return response()->json(['success' => false, 'message' => 'Fare range overlaps with an existing fare'], 400);
        }
        // Create the new standard fare
        $fare = StandardFare::create([
            'distance_range_start' => $request->distance_range_start,
            'distance_range_end' => $request->distance_range_end,
            'fare' => $request->fare,
        ]);

        return response()->json(['success' => true, 'data' => $fare], 201);
    }

    // Update an existing standard fare
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'distance_range_start' => 'required|numeric',
            'distance_range_end' => 'required|numeric|gt:distance_range_start',
            'fare' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 400);
        }

        // Find the fare record
        $fare = StandardFare::find($id);
        if (!$fare) {
            return response()->json(['success' => false, 'message' => 'Fare not found'], 404);
        }

        // Update the fare
        $fare->update([
            'distance_range_start' => $request->distance_range_start,
            'distance_range_end' => $request->distance_range_end,
            'fare' => $request->fare,
        ]);

        return response()->json(['success' => true, 'data' => $fare], 200);
    }

    // Delete a standard fare
    public function destroy($id)
    {
        // Find the fare record
        $fare = StandardFare::find($id);
        if (!$fare) {
            return response()->json(['success' => false, 'message' => 'Fare not found'], 404);
        }

        // Delete the fare
        $fare->delete();

        return response()->json(['success' => true, 'message' => 'Fare deleted successfully'], 200);
    }
}
