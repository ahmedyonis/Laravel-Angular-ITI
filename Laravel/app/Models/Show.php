<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Show extends Model
{
    //
    protected $fillable = [
    'title',
    'show_date',
    'show_time',
    'price_first_class',
    'price_second_class',
    'price_standard',
    'total_seats',
    'image'
];

    public function seats()
    {
        return $this->hasMany(Seat::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
