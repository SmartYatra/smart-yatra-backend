<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Services\ShortestRouteService;
use Illuminate\Http\Request;

class ShortestRouteController extends BaseController
{
    protected $shortestRouteService;

    public function __construct(ShortestRouteService $shortestRouteService)
    {
        $this->shortestRouteService = $shortestRouteService;
    }

    public function findShortestRoute(Request $request)
    {
        $request->validate([
            'start_stop_id' => 'required|integer|exists:stops,id',
            'end_stop_id' => 'required|integer|exists:stops,id',
        ]);

        $shortestRouteDetails = $this->shortestRouteService->findShortestRoute(
            $request->start_stop_id,
            $request->end_stop_id
        );

        if (empty($shortestRouteDetails)) {
            return $this->sendResponse(['success' => false, 'data' => [], 'message' => 'No route found.'], 200);
        }

        return response()->json([
            'shortest_route' => $shortestRouteDetails,
        ]);
    }
}
