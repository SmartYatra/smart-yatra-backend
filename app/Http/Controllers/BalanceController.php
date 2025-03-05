<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Auth;

class BalanceController extends BaseController
{
    public function getBalance()
    {
        $user = Auth::user();
        if (!$user) {
            return $this->sendResponse(['success' => false, 'message' => 'Passenger not found'], 400);
        }        
        $balance = $user->wallet ? $user->wallet->balance : 0;
        return $this->sendResponse(['success'=>true,'balance'=>$balance, 'message' => 'Balance Retrieved Successfully'],200);
    }
}
