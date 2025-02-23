<?php

namespace App\Http\Controllers;

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
}
