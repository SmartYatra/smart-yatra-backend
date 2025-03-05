<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use Illuminate\Support\Facades\Auth;

class BalanceController extends BaseController
{
    public function getBalance()
    {
        $user = Auth::user();
        
        // Check if the user exists
        if (!$user) {
            return $this->sendError('Passenger not found', [], 400);
        }

        // Get balance or set to 0 if no wallet exists
        $balance = $user->hasWallet ? $user->hasWallet->balance : 0;
        
        // Return the balance as part of the response
        return $this->sendResponse(['balance' => $balance], 'Balance Retrieved Successfully.');
    }
}

