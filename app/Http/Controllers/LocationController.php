<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use App\Models\City;
use App\Models\University;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class LocationController extends Controller
{
    public function governorates()
    {
        return response()->json([
            'success' => true,
            'data'    => Cache::remember('governorates.all', now()->addHours(24), fn () => Governorate::all()),
        ]);
    }

    public function cities($governorateId)
    {
        return response()->json([
            'success' => true,
            'data'    => Cache::remember("cities.governorate.{$governorateId}", now()->addHours(24), fn () => City::where('governorate_id', $governorateId)->get()),
        ]);
    }

    public function universities($cityId)
    {
        return response()->json([
            'success' => true,
            'data'    => Cache::remember("universities.city.{$cityId}", now()->addHours(24), fn () => University::where('city_id', $cityId)->get()),
        ]);
    }

    public function storeGovernorate(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:governorates,name',
        ]);

        $governorate = Governorate::create(['name' => $request->name]);

        Cache::forget('governorates.all');

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة المحافظة بنجاح',
            'data' => $governorate,
        ], 201);
    }

    public function storeCity(Request $request): JsonResponse
    {
        $request->validate([
            'governorate_id' => 'required|exists:governorates,id',
            'name' => 'required|string|max:255',
        ]);

        $city = City::create([
            'governorate_id' => $request->governorate_id,
            'name' => $request->name,
        ]);

        Cache::forget("cities.governorate.{$request->governorate_id}");

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة المدينة بنجاح',
            'data' => $city,
        ], 201);
    }

    public function storeUniversity(Request $request): JsonResponse
    {
        $request->validate([
            'city_id' => 'required|exists:cities,id',
            'name' => 'required|string|max:255',
        ]);

        $university = University::create([
            'city_id' => $request->city_id,
            'name' => $request->name,
        ]);

        Cache::forget("universities.city.{$request->city_id}");

        return response()->json([
            'success' => true,
            'message' => 'تمت إضافة الجامعة بنجاح',
            'data' => $university,
        ], 201);
    }
}
