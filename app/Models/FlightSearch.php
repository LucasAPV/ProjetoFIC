<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlightSearch extends Model
{
    protected $fillable = [
        'user_id',
        'origin',
        'destination',
        'departure_date',
        'return_date',
        'results'
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'results' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
