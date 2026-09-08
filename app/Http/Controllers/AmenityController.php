<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AmenityController extends Controller
{
    /** Return the database-managed amenities that owners can assign to a property. */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Cache::remember('amenities.all', now()->addHours(24), fn () => Amenity::query()->orderBy('name')->get(['id', 'name'])),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:amenities,name',
        ]);

        $amenity = Amenity::create([
            'name' => $request->name,
        ]);

        Cache::forget('amenities.all');

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة المرفق بنجاح',
            'data' => $amenity,
        ], 201);
    }

    public function destroy($id): JsonResponse
    {
        $amenity = Amenity::find($id);

        if (!$amenity) {
            return response()->json([
                'success' => false,
                'message' => 'المرفق غير موجود',
            ], 404);
        }

        $amenity->delete();

        Cache::forget('amenities.all');

        return response()->json([
            'success' => true,
            'message' => 'تم حذف المرفق بنجاح',
        ]);
    }
}
