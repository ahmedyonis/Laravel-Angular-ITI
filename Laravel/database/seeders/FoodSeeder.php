<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Food;

class FoodSeeder extends Seeder
{
    public function run()
    {
        Food::insert([
            ['name' => 'Popcorn', 'price' => 15.00],
            ['name' => 'Pepsi', 'price' => 10.00],
            ['name' => 'Nachos', 'price' => 20.00],
            ['name' => 'Hot Dog', 'price' => 25.00],
            ['name' => 'Ice Cream', 'price' => 12.00]
        ]);
    }
}