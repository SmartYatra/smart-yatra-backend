<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Services\PossibleRoutesService;
use Illuminate\Http\Request;

class PossibleRouteController extends BaseController
{
    protected $possibleRoutesService;

    public function __construct(PossibleRoutesService $possibleRoutesService)
    {
        $this->possibleRoutesService = $possibleRoutesService;
    }
    public function getAllRoutes(Request $request)
    {
        $request->validate([
            'start_stop_id' => 'required|integer|exists:stops,id',
            'end_stop_id' => 'required|integer|exists:stops,id',
        ]);

        $allRouteDetails = $this->possibleRoutesService->getAllRoutes(
            $request->start_stop_id,
            $request->end_stop_id
        );

        if (empty($allRouteDetails)) {
            return $this->sendResponse(['success' => false, 'data' => [], 'message' => 'No route found.'], 200);
        }

        return response()->json([
            'all_routes' => $allRouteDetails,
        ]);
    }
}
