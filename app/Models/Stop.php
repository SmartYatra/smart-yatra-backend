<?php

namespace App\Models;

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
        'location_lng'
    ];
    /**
     * The routes that the stop belongs to.
     */
    public function routes()
    {
        return $this->belongsToMany(Route::class, 'route_stop')
            ->withPivot('order'); //withPivot retrives the order column from the pivot table 'route_stop'
    }
}
