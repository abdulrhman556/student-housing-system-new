<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Services\AdminBookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function __construct(protected AdminBookingService $adminBookingService)
    {
    }

    /**
     * List all bookings with optional status and search filters.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $status = $request->query('status');
            $search = $request->query('search');

            $bookings = $this->adminBookingService->index($status, $search);

            return response()->json([
                'success' => true,
                'data' => BookingResource::collection($bookings),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Show a single booking for admin management.
     */
    public function show(Booking $booking): JsonResponse
    {
        try {
            $bookingDetails = $this->adminBookingService->show($booking);

            return response()->json([
                'success' => true,
                'data' => new BookingResource($bookingDetails),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Confirm the booking availability and reserve the unit.
     */
    public function confirmAvailability(Booking $booking): JsonResponse
    {
        try {
            $updatedBooking = $this->adminBookingService->confirmAvailability($booking);

            return response()->json([
                'success' => true,
                'message' => 'Booking availability confirmed.',
                'data' => new BookingResource($updatedBooking),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Reject the booking.
     */
    public function reject(Booking $booking): JsonResponse
    {
        try {
            $updatedBooking = $this->adminBookingService->reject($booking);

            return response()->json([
                'success' => true,
                'message' => 'Booking rejected.',
                'data' => new BookingResource($updatedBooking),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Cancel the booking and release the unit if needed.
     */
    public function cancel(Booking $booking): JsonResponse
    {
        try {
            $updatedBooking = $this->adminBookingService->cancel($booking);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled.',
                'data' => new BookingResource($updatedBooking),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
