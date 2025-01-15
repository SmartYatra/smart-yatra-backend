<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandardFare extends Model
{
    use HasFactory;

    protected $fillable = [
        'distance_range_start',
        'distance_range_end',
        'fare',
    ];
}
