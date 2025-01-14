<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//pivotfor many to many relationship of Route and Stop tables
class RouteStop extends Model
{
    use HasFactory;
        /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'order',
    ];
}
