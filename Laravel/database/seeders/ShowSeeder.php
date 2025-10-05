<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Show;

class ShowSeeder extends Seeder
{
    public function run()
    {
        $plays = [
            'Hamlet',
            'Romeo and Juliet',
            'A Midsummer Night\'s Dream',
            'The Tempest',
            'Macbeth'
        ];

        $baseDate = now()->startOfDay(); // ابدأ من اليوم

        foreach ($plays as $index => $title) {
            Show::create([
                'title' => $title,
                'show_date' => $baseDate->copy()->addDays($index)->format('Y-m-d'),
                'show_time' => '19:00:00',
                'price_first_class' => 120.00,
                'price_second_class' => 80.00,
                'price_standard' => 50.00,
                'total_seats' => 50,
                'image' => strtolower(str_replace(' ', '-', $title)) . '.jpg'
            ]);
        }
    }
}