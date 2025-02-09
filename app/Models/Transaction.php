<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'amount', 'type', 'description'];

    public static function createTransaction($userId, $amount, $type, $description = null)
    {
        return self::create([
            'user_id' => $userId,
            'amount' => $amount,
            'type' => $type,
            'description' => $description,
        ]);
    }
}

