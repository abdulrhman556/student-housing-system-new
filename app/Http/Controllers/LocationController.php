<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use App\Models\City;
use App\Models\University;
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
}
