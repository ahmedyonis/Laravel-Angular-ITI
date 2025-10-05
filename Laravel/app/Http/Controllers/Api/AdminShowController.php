<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Show;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminShowController extends Controller
{
    // عرض كل العروض (للـ admin)
    public function index(): JsonResponse
    {
        return response()->json(Show::all());
    }

    // إضافة عرض جديد
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'show_date' => 'required|date',
            'show_time' => 'required|date_format:H:i',
            'price_first_class' => 'required|numeric|min:0',
            'price_second_class' => 'required|numeric|min:0',
            'price_standard' => 'required|numeric|min:0',
            'total_seats' => 'required|integer|min:1|max:200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only([
            'title', 'show_date', 'show_time',
            'price_first_class', 'price_second_class', 'price_standard',
            'total_seats'
        ]);

        // رفع الصورة (اختياري)
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('shows', 'public');
            $data['image'] = basename($path);
        }

        $show = Show::create($data);

        return response()->json($show, 201);
    }

    // تعديل عرض
    public function update(Request $request, Show $show): JsonResponse
    {
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'show_date' => 'sometimes|date',
            'show_time' => 'sometimes|date_format:H:i',
            'price_first_class' => 'sometimes|numeric|min:0',
            'price_second_class' => 'sometimes|numeric|min:0',
            'price_standard' => 'sometimes|numeric|min:0',
            'total_seats' => 'sometimes|integer|min:1|max:200',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $data = $request->only([
            'title', 'show_date', 'show_time',
            'price_first_class', 'price_second_class', 'price_standard',
            'total_seats'
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
        $show->delete();
        return response()->json(['message' => 'Show deleted successfully']);
    }
}