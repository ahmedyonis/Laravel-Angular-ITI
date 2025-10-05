<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    //
    protected $fillable = [
    'user_id',
    'show_id',
    'total_amount',
    'status'
];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function show()
    {
        return $this->belongsTo(Show::class);
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'booking_seats');
    }

    public function foodItems()
    {
    return $this->belongsToMany(Food::class, 'booking_food', 'booking_id', 'food_item_id')
                ->withPivot('quantity');
    }
}
