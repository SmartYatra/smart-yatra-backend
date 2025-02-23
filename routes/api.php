<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\BusController;
use App\Http\Controllers\BusLocationController;
use App\Http\Controllers\BusQrController;
use App\Http\Controllers\PassengerTripController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\ShortestRouteController;
use App\Http\Controllers\StandardFareController;
use App\Http\Controllers\StopController;
use App\Http\Controllers\TripController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Models\StopConnection;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
   Route::apiResource('routes', RouteController::class);
   Route::apiResource('buses', BusController::class);

   Route::get('/buses/{busId}/qr-data', [BusQrController::class, 'getQrData']);

   Route::post('/trips/scan', [PassengerTripController::class, 'scan']);

   Route::post('/buses/start-trip', [TripController::class, 'startTrip']);
   Route::post('/buses/end-trip', [TripController::class, 'endTrip']);

   Route::get('/shortest-route', [ShortestRouteController::class, 'findShortestRoute']);

   Route::prefix('bus')->group(function () {
      Route::post('{busId}/update-location', [BusLocationController::class, 'updateLocation']);
      Route::get('get-nearby', [BusLocationController::class, 'getNearbyBuses']);
   });

   Route::apiResource('standard-fares', StandardFareController::class);

   Route::prefix('wallet')->group(function(){
      Route::get('transaction-history',[WalletController::class, 'getTransactionHistory']);
      Route::get('current-balance',[WalletController::class,'getCurrentBalance']);
   });

   Route::get('/users/{userId}/notifications', [UserController::class, 'getNotifications']);
   Route::get('/stops',[StopController::class,'index']);
});
