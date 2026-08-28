<?php

namespace App\Http\Controllers;

use App\Models\Amenity;
use Illuminate\Http\JsonResponse;

class AmenityController extends Controller
{
    /** Return the database-managed amenities that owners can assign to a property. */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Amenity::query()->orderBy('name')->get(['id', 'name']),
        ]);
    }
}
