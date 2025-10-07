<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Models\Seat;

class AdminShowController extends Controller
{
    private function createSeatsForShow($show): void
{
    $seatsData = [];

    // A1 - A10 → first class
    for ($i = 1; $i <= 10; $i++) {
        $seatsData[] = [
            'show_id' => $show->id,
            'seat_number' => 'A' . $i,
            'seat_class' => 'first',
            'is_booked' => false,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    // B1 - B10 → second class
    for ($i = 1; $i <= 10; $i++) {
        $seatsData[] = [
            'show_id' => $show->id,
            'seat_number' => 'B' . $i,
            'seat_class' => 'second',
            'is_booked' => false,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    // C1 - C10 → second class (حسب هيكلك السابق)
    for ($i = 1; $i <= 10; $i++) {
        $seatsData[] = [
            'show_id' => $show->id,
            'seat_number' => 'C' . $i,
            'seat_class' => 'second',
            'is_booked' => false,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    // D1 - D10 → standard
    for ($i = 1; $i <= 10; $i++) {
        $seatsData[] = [
            'show_id' => $show->id,
            'seat_number' => 'D' . $i,
            'seat_class' => 'standard',
            'is_booked' => false,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    // E1 - E10 → standard
    for ($i = 1; $i <= 10; $i++) {
        $seatsData[] = [
            'show_id' => $show->id,
            'seat_number' => 'E' . $i,
            'seat_class' => 'standard',
            'is_booked' => false,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }

    // إدخال كل المقاعد دفعة واحدة
    Seat::insert($seatsData);
}
    
    
    
    // عرض كل العروض (للـ admin)
    public function index(): JsonResponse
    {
        $shows = Show::all();
        $shows->each(function ($show) {
        if ($show->image) {
            $show->image = asset('storage/shows/' . $show->image);
        }
    });
    return response()->json($shows);
    }

    // إضافة عرض جديد
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'price_first_class' => 'required|numeric|min:0',
            'price_second_class' => 'required|numeric|min:0',
            'price_standard' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only([
        'title', 'price_first_class', 'price_second_class', 'price_standard'
    ]);

    // تحديد التاريخ والعدد تلقائيًا
    $data['show_date'] = now()->addDays(7)->toDateString(); // مثلاً بعد أسبوع
    $data['show_time'] = '19:00:00';
    $data['total_seats'] = 50;

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('shows', 'public');
        $data['image'] = basename($path);
    }



    $show = Show::create($data);

    $this->createSeatsForShow($show);

    return response()->json($show, 201);
}

    

    // تعديل عرض
    public function update(Request $request, Show $show): JsonResponse
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',

            'price_first_class' => 'sometimes|numeric|min:0',
            'price_second_class' => 'sometimes|numeric|min:0',
            'price_standard' => 'sometimes|numeric|min:0',

            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only([
            'title',
            'price_first_class', 'price_second_class', 'price_standard',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('shows', 'public');
            $data['image'] = basename($path);
        }

        $show->update($data);

        return response()->json($show);
    }

    // حذف عرض
    public function destroy(Show $show): JsonResponse
    {
        $show->seats()->delete();
        $show->delete();
        return response()->json(['message' => 'Show deleted successfully']);
    }
}