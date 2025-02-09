<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;
    protected $fillable = [
        'bus_number',
        'model',
        'capacity',
        'status',
        'longitude',
        'latitude',
        'driver_id'
    ];

    public function driver()
    {
        return $this->belongsTo(User::class,'driver_id');
    }
}
