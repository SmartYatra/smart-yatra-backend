<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StopConnection extends Model
{
        /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'stop_id',
        'next_stop_id',
        'distance'
    ];
}
