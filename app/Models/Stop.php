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
        'lattitude',
        'longitude'
    ];
    /**
     * The routes that the stop belongs to.
     */
    public function stops()
    {
        return $this->belongsToMany(Stop::class, 'route_stop')
            ->withPivot('order'); //withPivot retrives the order column from the pivot table 'route_stop'
    }
}
