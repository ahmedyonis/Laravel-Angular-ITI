<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    //
    protected $table = 'food_items';

    
    protected $fillable = [
    'name',
    'price'
];

    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_food')
                    ->withPivot('quantity');
    }
}
