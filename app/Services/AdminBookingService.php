<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingHistory;
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
            ->with([
                'student',
                'unit.property',
            ])
            ->latest();

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $searchTerm = trim($search);

            if ($searchTerm !== '') {
                $query->whereHas('student', function (Builder $studentQuery) use ($searchTerm): void {
                    $driver = DB::connection()->getDriverName();
                    $concatExpression = $driver === 'sqlite'
                        ? "(fname || ' ' || lname)"
                        : "CONCAT(fname, ' ', lname)";

                    $studentQuery->where('fname', 'like', "%{$searchTerm}%")
                        ->orWhere('lname', 'like', "%{$searchTerm}%")
                        ->orWhereRaw("{$concatExpression} LIKE ?", ["%{$searchTerm}%"])
                        ->orWhere('email', 'like', "%{$searchTerm}%");
                })
                    ->orWhereHas('unit.property', function (Builder $propertyQuery) use ($searchTerm): void {
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
        return $booking->load([
            'student',
            'unit.property',
        ]);
    }

    /**
     * Confirm booking availability and reserve one unit slot.
     */
public function confirmAvailability(Booking $booking): Booking
{
    return DB::transaction(function () use ($booking): Booking {
        // Lock the booking itself as well as the unit. This prevents two admins
        // from approving the same request and reserving two slots.
        $booking = Booking::query()->lockForUpdate()->findOrFail($booking->id);

        if ($booking->status !== 'pending') {
            throw new RuntimeException('Only pending bookings can be confirmed.');
        }

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

        $adminId = auth('admin')->id();

        $booking->update([
            'status' => 'availability_confirmed',
            'admin_id' => $adminId,
        ]);

        $this->createHistory($booking, $adminId, 'availability_confirmed', 'Booking availability confirmed by admin.');

        // One confirmed booking reserves exactly one bed/slot.  The lock above
        // makes this safe when two admins act on requests at the same time.
        $unit->decrement('available_count');
        $unit->refresh();
        $unit->update([
            'status' => $unit->available_count > 0 ? 'available' : 'occupied',
        ]);

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
            'unit.property',
        ]);
    });
}

    /**
     * Reject a pending booking without changing availability.
     */
public function reject(Booking $booking): Booking
{
    return DB::transaction(function () use ($booking): Booking {
        $booking = Booking::query()->lockForUpdate()->findOrFail($booking->id);

        if ($booking->status !== 'pending') {
            throw new RuntimeException('Only pending bookings can be rejected.');
        }

        $adminId = auth('admin')->id();

        $booking->update([
            'status' => 'rejected',
            'admin_id' => $adminId,
        ]);

        $this->createHistory($booking, $adminId, 'rejected', 'Booking rejected by admin.');

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
            'unit.property',
        ]);
    });
}

/**
 * Cancel a booking and restore the unit capacity if it had been confirmed.
 */
public function cancel(Booking $booking): Booking
{
    return DB::transaction(function () use ($booking): Booking {
        $booking = Booking::query()->lockForUpdate()->findOrFail($booking->id);

        if (in_array($booking->status, ['completed', 'rejected', 'cancelled'], true)) {
            throw new RuntimeException('This booking cannot be cancelled.');
        }

        if ($booking->status === 'availability_confirmed') {

            $unit = $booking->unit()->lockForUpdate()->first();

            if ($unit) {
                $unit->increment('available_count');
                $unit->refresh();
                $unit->update([
                    'status' => $unit->available_count > 0 ? 'available' : 'occupied',
                ]);
            }
        }

        $adminId = auth('admin')->id();

        $booking->update([
            'status' => 'cancelled',
            'admin_id' => $adminId,
        ]);

        $this->createHistory($booking, $adminId, 'cancelled', 'Booking cancelled by admin.');

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
            'unit.property',
        ]);
    });
}

protected function createHistory(Booking $booking, ?int $adminId, string $status, ?string $note = null): void
{
    BookingHistory::query()->create([
        'booking_id' => $booking->id,
        'admin_id' => $adminId,
        'status' => $status,
        'note' => $note,
    ]);
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
        \App\Jobs\SendTelegramNotification::dispatch([
            'chat_id' => config('services.telegram.chat_id'),
            'message' => 'Booking #' . $booking->id . ': ' . $message,
        ]);
    }
}
