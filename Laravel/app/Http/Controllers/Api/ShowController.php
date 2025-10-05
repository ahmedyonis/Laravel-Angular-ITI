<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Show;
use Illuminate\Http\JsonResponse;

class ShowController extends Controller
{
    public function index(): JsonResponse
    {
        $shows = Show::select('id', 'title', 'show_date', 'show_time', 'image')
            ->withCount('seats as total_seats')
            ->get()
            ->map(function ($show) {
                $show->image = $show->image ? asset('storage/shows/' . $show->image) : null;
                return $show;
            });

        return response()->json($shows);
    }

    public function show(Show $show): JsonResponse
    {
        $seats = $show->seats()->select('id', 'seat_number', 'seat_class', 'is_booked')->get();

        $show->image = $show->image ? asset('storage/shows/' . $show->image) : null;

        return response()->json([
            'show' => $show,
            'seats' => $seats
        ]);
    }
}