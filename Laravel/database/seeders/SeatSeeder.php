<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Seat;
use App\Models\Show;

class SeatSeeder extends Seeder
{
    public function run()
    {
        $shows = Show::all();

        foreach ($shows as $show) {
            $seatCount = 0;
            for ($row = 'A'; $row <= 'E'; $row++) {
                for ($num = 1; $num <= 10; $num++) {
                    if ($seatCount >= 50) break 2; // تأكد أن العدد = 50 بالضبط

                    $class = match($row) {
                        'A' => 'first',
                        'B', 'C' => 'second',
                        default => 'standard'
                    };

                    Seat::create([
                        'show_id' => $show->id,
                        'seat_number' => "$row$num",
                        'seat_class' => $class,
                        'is_booked' => false
                    ]);

                    $seatCount++;
                }
            }
        }
    }
}