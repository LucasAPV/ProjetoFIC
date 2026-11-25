<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    //
    protected $fillable = [
        'flight_search_id',
        'departure_airport_name',
        'arrival_airport_name',
        'departure_at',
        'arrival_at',
        'duration_minutes',
        'stops',
        'price',
    ];
    protected $casts = [
        'departure_at' => 'datetime',
        'arrival_at' => 'datetime',
    ];
    public function flightSearch()
    {
        return $this->belongsTo(FlightSearch::class);
    }
}
