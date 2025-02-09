<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassengerTrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'passenger_id',
        'trip_id',
        'boarding_time',
        'alighting_time',
        'boarding_stop_id',
        'alighting_stop_id',
        'fare',
    ];

    // Relationships
    public function passenger()
    {
        return $this->belongsTo(User::class);
    }

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }

    public function boardingStop()
    {
        return $this->belongsTo(Stop::class, 'boarding_stop_id');
    }

    public function alightingStop()
    {
        return $this->belongsTo(Stop::class, 'alighting_stop_id');
    }
}
