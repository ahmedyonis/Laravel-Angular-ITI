<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    // عرض حجوزات المستخدم
    public function index(): JsonResponse
    {
        $bookings = auth()->user()->bookings()
            ->with(['show:id,title,show_date,show_time', 'seats:id,seat_number,seat_class'])
            ->get();

        return response()->json($bookings);
    }

    // إنشاء حجز جديد
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'show_id' => 'required|exists:shows,id',
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'exists:seats,id|integer',
            'food_items' => 'nullable|array',
            'food_items.*.id' => 'exists:food_items,id',
            'food_items.*.quantity' => 'required_with:food_items|integer|min:1'
        ]);

        // التأكد أن كل المقاعد متاحة ومرتبطة بنفس العرض
        $seats = Seat::whereIn('id', $request->seat_ids)
            ->where('show_id', $request->show_id)
            ->where('is_booked', false)
            ->get();

        if ($seats->count() !== count($request->seat_ids)) {
            throw ValidationException::withMessages([
                'seat_ids' => 'Some seats are not available or do not belong to this offer.'
            ]);
        }

        // حساب السعر
        $show = $seats->first()->show;
        $seatPrice = 0;
        foreach ($seats as $seat) {
            $seatPrice += match($seat->seat_class) {
                'first' => $show->price_first_class,
                'second' => $show->price_second_class,
                default => $show->price_standard,
            };
        }

        $foodPrice = 0;
        if ($request->filled('food_items')) {
            foreach ($request->food_items as $item) {
                $food = \App\Models\Food::find($item['id']);
                $foodPrice += $food->price * $item['quantity'];
            }
        }

        $total = $seatPrice + $foodPrice;

        // بدء معاملة (Transaction)
        DB::transaction(function () use ($request, $seats, $total, &$booking) {
            // 1. إنشاء الحجز
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'show_id' => $request->show_id,
                'total_amount' => $total,
                'status' => 'confirmed'
            ]);

            // 2. ربط المقاعد
            foreach ($seats as $seat) {
                $seat->update(['is_booked' => true]);
                $booking->seats()->attach($seat->id);
            }

            // 3. ربط الطعام
            if ($request->filled('food_items')) {
                $foodData = collect($request->food_items)->mapWithKeys(function ($item) {
                    return [$item['id'] => ['quantity' => $item['quantity']]];
                });
                $booking->foodItems()->attach($foodData);
            }
        });

        return response()->json($booking->load('seats', 'foodItems'), 201);
    }

    // إلغاء حجز
    public function cancel(Booking $booking): JsonResponse
    {
        if ($booking->user_id !== auth()->id()) {
            return response()->json(['message' => 'Not authorized'], 403);
        }

        if ($booking->status === 'cancelled') {
            return response()->json(['message' => 'The reservation is already cancelled.'], 400);
        }

        DB::transaction(function () use ($booking) {
            $booking->update(['status' => 'cancelled']);

            // تحرير المقاعد
            $seatIds = $booking->seats->pluck('id');
            Seat::whereIn('id', $seatIds)->update(['is_booked' => false]);
        });

        return response()->json(['message' => 'Cancelled successfully']);
    }
}