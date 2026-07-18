<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;

use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use App\Services\BookingService;

class BookingController extends Controller
{
    // Store a new booking .

    public function store(
        StoreBookingRequest $request,
        BookingService $bookingService
    ): JsonResponse {
        try {
            $booking = $bookingService->store($request->validated());

            return response()->json([
                'message' => 'Booking created successfully.',
                'data' => $booking,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    
    public function index()
    {

    }

    public function show(Booking $booking)
    {

    }

    public function update(Request $request, Booking $booking)
    {

    }

    public function destroy(Booking $booking)
    {

    }

}
