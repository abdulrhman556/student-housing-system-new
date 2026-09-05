<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\JsonResponse;
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
}
