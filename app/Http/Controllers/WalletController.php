<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\BaseController;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends BaseController
{
    /**
     * Get the current balance of the user's wallet
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentBalance(Request $request)
    {
        // Validate the user_id passed in the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Retrieve the wallet for the user
        $wallet = Wallet::where('user_id', $request->user_id)->first();

        // Check if the wallet exists
        if (!$wallet) {
            return $this->sendError('Wallet not found for this user.');
        }

        // Return the current balance from the wallet
        return $this->sendResponse(['balance' => $wallet->balance], 'Current balance retrieved successfully');
    }
    /**
     * Retrieve the transaction history of a user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTransactionHistory(Request $request)
    {
        // Validate the user_id passed in the request
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Retrieve the transaction history of the user
        $transactions = Transaction::where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc')  // Ordering by most recent transaction
            ->get();

        // Check if transactions exist
        if ($transactions->isEmpty()) {
            return $this->sendError('No transaction history found for this user.');
        }

        // Return the transaction history
        return $this->sendResponse($transactions, 'Transaction history retrieved successfully');
    }
}
