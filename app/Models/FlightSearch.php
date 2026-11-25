<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightSearch extends Model
{
    protected $fillable = [
        'origin',
        'destination',
        'departure_date',
        'return_date',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
    ];

    public function flights()
    {
        return $this->hasMany(Flight::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
