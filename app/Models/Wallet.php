<?php

namespace App\Models;

use App\Notifications\BalanceUpdatedNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'balance'];
    // Make sure balance defaults to 0 if not set
    protected $attributes = [
        'balance' => 0,
    ];
    // A wallet belongs to a user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Method to add funds to the wallet
    public static function topUp($userId, $amount)
    {
        $wallet = self::firstOrCreate(['user_id' => $userId]);
        $wallet->balance += $amount;
        $wallet->save();
        Transaction::createTransaction($userId, $amount, 'top_up', 'Wallet top-up');

        // Send a notification to the user
        $user = $wallet->user;
        $user->notify(new BalanceUpdatedNotification($wallet->balance));
        return $wallet;
    }

    // Method to deduct funds from the wallet
    public static function deduct($userId, $amount)
    {
        $wallet = self::where('user_id', $userId)->first();

        if ($wallet && $wallet->balance >= $amount) {
            $wallet->balance -= $amount;
            $wallet->save();
            Transaction::createTransaction($userId, $amount, 'fare_deduction', 'Fare deduction for trip');

            // Send a notification to the user
            $user = $wallet->user;
            $user->notify(new BalanceUpdatedNotification($wallet->balance));
            return true;
        }

        return false; // Insufficient funds
    }
}
