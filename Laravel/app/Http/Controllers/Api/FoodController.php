<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Food;
use Illuminate\Http\JsonResponse;

class FoodController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Food::all());
    }
}