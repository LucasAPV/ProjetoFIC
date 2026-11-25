<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    //
    protected $fillable = [
        'duration_minutes',
        'stops',
        'price',
    ];
    public function flightSearch()
    {
        return $this->belongsTo(FlightSearch::class);
    }
}
