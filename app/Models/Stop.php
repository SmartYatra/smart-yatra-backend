<?php

namespace App\Models;

use App\Helpers\GeoHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'location_lat',
        'location_lng',
        'geohash'
    ];
    /**
     * The routes that the stop belongs to.
     */
    public function routes()
    {
        return $this->belongsToMany(Route::class, 'route_stop')
            ->withPivot('order'); //withPivot retrives the order column from the pivot table 'route_stop'
    }

    /**
     * Boot method to generate geohash before saving.
     */
    protected static function booted()
    {
        static::saving(function ($stop) {
            // Automatically generate and set the geohash before saving the Stop
            $stop->geohash = GeoHelper::encodeGeohash($stop->location_lat, $stop->location_lng, 7); // You can adjust precision as needed
        });
    }
}
