<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class AdminBookingService
{
    public function __construct(
        protected NotificationService $notificationService,
        protected TelegramService $telegramService
    ) {
    }

    /**
     * List bookings with optional filters for status and text search.
     */
    public function index(?string $status = null, ?string $search = null)
    {
        $query = Booking::query()
            ->with(['student', 'property', 'unit'])
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $searchTerm = trim($search);

            if ($searchTerm !== '') {
                $query->whereHas('student', function (Builder $studentQuery) use ($searchTerm): void {
                    $studentQuery->where('fname', 'like', "%{$searchTerm}%")
                        ->orWhere('lname', 'like', "%{$searchTerm}%")
                        ->orWhere(DB::raw("CONCAT(fname, ' ', lname)"), 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%");
                })
                    ->orWhereHas('property', function (Builder $propertyQuery) use ($searchTerm): void {
                        $propertyQuery->where('title', 'like', "%{$searchTerm}%");
                    });
            }
        }

        return $query->get();
    }

    /**
     * Show the full booking details for admin review.
     */
    public function show(Booking $booking): Booking
    {
        return $booking->load(['student', 'property', 'unit']);
    }

    /**
     * Confirm booking availability and reserve one unit slot.
     */
public function confirmAvailability(Booking $booking): Booking
{
    if ($booking->status !== 'pending') {
        throw new RuntimeException(
            'Only pending bookings can be confirmed.'
        );
    }

    return DB::transaction(function () use ($booking): Booking {

        $unit = $booking->unit()->lockForUpdate()->first();

        if (! $unit) {
            throw new RuntimeException(
                'The associated unit was not found.'
            );
        }

        if ((int) $unit->available_count <= 0) {
            throw new RuntimeException(
                'No available places left for this unit.'
            );
        }

        $booking->update([
            'status' => 'availability_confirmed',
        ]);

        $unit->decrement('available_count');

        $this->sendBookingNotification(
            $booking,
            'booking_confirmed',
            'Booking Confirmed',
            'Your booking has been confirmed. Please complete your payment within the allowed time.'
        );

        $this->sendTelegramNotification(
            $booking,
            'Booking availability confirmed.'
        );

        return $booking->fresh([
            'student',
            'property',
            'unit',
        ]);
    });
}

    /**
     * Reject a pending booking without changing availability.
     */
public function reject(Booking $booking): Booking
{
    if ($booking->status !== 'pending') {
        throw new RuntimeException(
            'Only pending bookings can be rejected.'
        );
    }

    return DB::transaction(function () use ($booking): Booking {
        $booking->update([
            'status' => 'rejected',
        ]);

        $this->sendBookingNotification(
            $booking,
            'booking_rejected',
            'Booking Rejected',
            'Unfortunately, this unit is no longer available.'
        );

        $this->sendTelegramNotification(
            $booking,
            'Booking rejected.'
        );

        return $booking->fresh([
            'student',
            'property',
            'unit',
        ]);
    });
}

/**
 * Cancel a booking and restore the unit capacity if it had been confirmed.
 */
public function cancel(Booking $booking): Booking
{
    if (in_array($booking->status, [
        'completed',
        'rejected',
        'cancelled',
    ], true)) {
        throw new RuntimeException(
            'This booking cannot be cancelled.'
        );
    }

    return DB::transaction(function () use ($booking): Booking {

        if ($booking->status === 'availability_confirmed') {

            $unit = $booking->unit()->lockForUpdate()->first();

            if ($unit) {
                $unit->increment('available_count');
            }
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        $this->sendBookingNotification(
            $booking,
            'booking_cancelled',
            'Booking Cancelled',
            'Your booking has been cancelled.'
        );

        $this->sendTelegramNotification(
            $booking,
            'Booking cancelled.'
        );

        return $booking->fresh([
            'student',
            'property',
            'unit',
        ]);
    });
}

protected function sendBookingNotification(
    Booking $booking,
    string $type,
    string $title,
    string $body
): void {
    $this->notificationService->send([
        'user_id' => $booking->student_id,
        'type' => $type,
        'title' => $title,
        'body' => $body,
        'data' => [
            'booking_id' => $booking->id,
            'status' => $booking->status,
        ],
    ]);
}

    protected function sendTelegramNotification(Booking $booking, string $message): void
    {
        $this->telegramService->send([
            'chat_id' => config('services.telegram.chat_id'),
            'message' => 'Booking #' . $booking->id . ': ' . $message,
        ]);
    }
}
