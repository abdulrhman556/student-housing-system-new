<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function store(StoreBookingRequest $request): JsonResponse {
        $data = $request->validated();
        $data['student_id'] = 1; // $data['student_id'] = auth()->id();
        $booking = Booking::create($data);
        return response()->json($booking, 201);
    }

}
