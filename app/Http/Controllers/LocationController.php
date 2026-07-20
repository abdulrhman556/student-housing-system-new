<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use App\Models\City;
use App\Models\University;

class LocationController extends Controller
{
    public function governorates()
    {
        return response()->json([
            'success' => true,
            'data'    => Governorate::all(),
        ]);
    }

    public function cities($governorateId)
    {
        return response()->json([
            'success' => true,
            'data'    => City::where('governorate_id', $governorateId)->get(),
        ]);
    }

    public function universities($cityId)
    {
        return response()->json([
            'success' => true,
            'data'    => University::where('city_id', $cityId)->get(),
        ]);
    }
}
