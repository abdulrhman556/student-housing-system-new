<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingStatusRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    /**
     * Display all bookings.
     */
    public function index()
    {
        $bookings = $this->bookingService->index();

        return BookingResource::collection($bookings);
    }

    /**
     * Store a new booking.
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingService->store($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully.',
                'data' => new BookingResource($booking),
            ], 201);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Display a booking.
     */
    public function show(Booking $booking): BookingResource
    {
        return new BookingResource(
            $this->bookingService->show($booking)
        );
    }

    /**
     * Update booking status.
     */
    public function updateStatus(
        UpdateBookingStatusRequest $request,
        Booking $booking
    ): JsonResponse {

        try {

            $booking = $this->bookingService->updateStatus(
                $booking,
                $request->validated()['status'],
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Booking status updated successfully.',
                'data' => new BookingResource($booking),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    /**
     * Cancel booking.
     */
    public function cancel(Booking $booking): JsonResponse
    {
        try {

            $booking = $this->bookingService->cancel($booking);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully.',
                'data' => new BookingResource($booking),
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);

        }
    }

    /**
     * Booking history.
     */
    public function history(Booking $booking): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->bookingService->history($booking),
        ]);
    }
}
