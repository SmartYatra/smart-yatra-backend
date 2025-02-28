<?php

namespace App\Models;

use App\Helpers\GeoHelper;
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
        'geohash',
        'driver_id',
        'route_id'
    ];

    public function driver()
    {
        return $this->belongsTo(User::class,'driver_id');
    }

    // Automatically generate geohash before saving
    public static function boot()
    {
        parent::boot();
        
        static::saving(function ($bus) {
            if ($bus->latitude && $bus->longitude) {
                $bus->geohash = GeoHelper::encodeGeohash($bus->latitude, $bus->longitude);
            }
        });
    }

}
