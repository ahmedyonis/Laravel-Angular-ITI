<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    //
    protected $fillable = [
    'show_id',
    'seat_number',
    'seat_class',
    'is_booked'
];


    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_seats');
    }
}
