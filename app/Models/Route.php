<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'distance',
        'duration'
    ];
    /**
     * The stops that belong to the route.
     */
    public function stops()
    {
        return $this->hasMany(Stop::class, 'route_stop')
            ->withPivot('order'); //withPivot retrives the order column from the pivot table 'route_stop'
    }

    /**
     * Get the first stop of the route.
     *
     * @return \App\Models\Stop|null
     */
    public function firstStop()
    {
        return $this->stops()
            ->orderBy('order', 'asc')  // Order by the 'order' column in ascending order
            ->first();  // Get the first stop
    }

    /**
     * Get the last stop of the route.
     *
     * @return \App\Models\Stop|null
     */
    public function lastStop()
    {
        return $this->stops()
            ->orderBy('order', 'desc')  // Order by the 'order' column in descending order
            ->first();  // Get the last stop
    }
}
