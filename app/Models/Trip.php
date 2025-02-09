<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_id',
        'route_id',
        'start_time',
        'end_time',
        'status',
        'current_passenger_count',
        'total_passenger_count',
        'total_fare_collected',
        'distance_traveled',
    ];

    // Relationships
    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

}

