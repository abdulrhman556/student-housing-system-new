<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingHistory;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class BookingService
{
    private const ACTIVE_BOOKING_STATUSES = ['pending', 'contacting_owner', 'contacting_student'];

    /**
     * Create a new booking request for the authenticated student.
     */
    public function store(array $data): Booking
    {
        $studentId = $data['student_id'] ?? Auth::id();
        $unitId = $data['unit_id'] ?? null;
        $checkInDate = $data['check_in_date'] ?? null;

        if (! $unitId) {
            throw new InvalidArgumentException('The unit ID is required.');
        }

        if (! $checkInDate) {
            throw new InvalidArgumentException('The check-in date is required.');
        }

        if (! $studentId) {
            throw new RuntimeException('The student is not authenticated.');
        }

        return DB::transaction(function () use ($data, $studentId, $unitId, $checkInDate): Booking {
            $unit = Unit::query()->findOrFail($unitId);

            if ($unit->status !== 'available') {
                throw new RuntimeException('This unit is not available.');
            }

            if ((int) $unit->available_count <= 0) {
                throw new RuntimeException('No available places.');
            }

            $activeBookingExists = Booking::query()
                ->where('student_id', $studentId)
                ->whereIn('status', self::ACTIVE_BOOKING_STATUSES)
                ->exists();

            if ($activeBookingExists) {
                throw new RuntimeException('You already have an active booking.');
            }

            $booking = Booking::query()->create([
                'student_id' => $studentId,
                'unit_id' => $unit->id,
                'status' => 'pending',
                'booking_date' => now()->toDateString(),
                'check_in_date' => $checkInDate,
            ]);

            $this->createHistory($booking, $data['admin_id'] ?? null, 'pending', $data['note'] ?? 'Booking created.');
            $this->notifyAdmin($booking, $data['admin_id'] ?? null);

            return $booking->fresh([
                'student',
                'unit.property',
                'history',
            ]);
        });
    }

    public function index()
    {
        return Booking::query()
            ->with([
                'student',
                'unit.property',
                'history',
            ])
            ->latest()
            ->get();
    }

    public function show(Booking|int $booking): Booking
    {
        $bookingModel = $this->resolveBooking($booking);

        return $bookingModel->load([
            'student',
            'unit.property',
            'history',
        ]);
    }

    /**
     * Update the booking status and keep the booking history in sync.
     */
    public function updateStatus(Booking|int $booking, string $status, array $data = []): Booking
    {
        $this->ensureAdminAccess();

        $bookingModel = $this->resolveBooking($booking);

        $this->ensureValidStatus($status);

        if ($status === 'completed' && ! $this->paymentIsVerified($bookingModel)) {
            throw new RuntimeException('Booking cannot be completed until payment is verified.');
        }

        return DB::transaction(function () use ($bookingModel, $status, $data): Booking {
            $oldStatus = $bookingModel->status;

            if ($oldStatus === $status) {
                return $bookingModel->fresh([
                    'student',
                    'unit.property',
                    'history',
                ]);
            }

            $bookingModel->status = $status;
            $bookingModel->save();

            if ($oldStatus !== 'cancelled' && $status === 'cancelled') {
                $unit = Unit::query()->find($bookingModel->unit_id);

                if ($unit) {
                    $unit->increment('available_count');
                }
            }

            $this->createHistory($bookingModel, $data['admin_id'] ?? null, $status, $data['note'] ?? 'Booking status updated.');
            $this->notifyStudent($bookingModel, $status);

            return $bookingModel->fresh([
                'student',
                'unit.property',
                'history',
            ]);
        });
    }

    public function history(Booking|int $booking)
    {
        $bookingModel = $this->resolveBooking($booking);

        return $bookingModel->history()->latest()->get();
    }

    public function cancel(Booking|int $booking): Booking
    {
        $this->ensureAdminAccess();

        $bookingModel = $this->resolveBooking($booking);

        return $this->updateStatus($bookingModel, 'cancelled', [
            'note' => 'Booking cancelled by admin.',
        ]);
    }

    protected function resolveBooking(Booking|int $booking): Booking
    {
        if ($booking instanceof Booking) {
            return $booking;
        }

        return Booking::query()->findOrFail($booking);
    }

    protected function createHistory(Booking $booking, ?int $adminId = null, string $status, ?string $note = null): BookingHistory
    {
        return BookingHistory::query()->create([
            'booking_id' => $booking->id,
            'admin_id' => $this->resolveAdminId($adminId),
            'status' => $status,
            'note' => $note,
        ]);
    }

    /**
     * Admin notifications are intentionally disabled because notifications.user_id
     * points to the users table while admins live in a separate admins table.
     * TODO: move this to the admin notification channel once supported by the architecture.
     */
    protected function notifyAdmin(Booking $booking, ?int $adminId = null): void
    {
        unset($booking, $adminId);
    }

    protected function notifyStudent(Booking $booking, string $status): Notification
    {
        return app(NotificationService::class)->send([
            'user_id' => $booking->student_id,
            'type' => 'booking_status_updated',
            'title' => 'Booking status updated',
            'body' => 'Your booking status has been updated to ' . $status . '.',
            'data' => ['booking_id' => $booking->id, 'status' => $status],
        ]);
    }

 
    protected function resolveAdminId(?int $adminId = null): ?int
    {
        if ($adminId !== null) {
            return $adminId;
        }

        return Auth::guard('admin')->id() ?: null;
    }

    private function ensureValidStatus(string $status): void
    {
        if (! in_array($status, ['pending', 'contacting_owner', 'contacting_student', 'availability_confirmed', 'completed', 'cancelled'], true)) {
            throw new InvalidArgumentException('Invalid booking status.');
        }
    }

    private function ensureAdminAccess(): void
    {
        if (! Auth::guard('admin')->check()) {
            throw new RuntimeException('Only admins can update booking status.');
        }
    }

    private function paymentIsVerified(Booking $booking): bool
    {
        return Payment::query()
            ->where('booking_id', $booking->id)
            ->where('status', 'verified')
            ->exists();
    }

protected function resolveAdminId(?int $adminId = null): int
{
    if ($adminId) {
        return $adminId;
    }

    $admin = \App\Models\Admin::query()->first();

    if ($admin) {
        return $admin->id;
    }

    throw new RuntimeException('No admin user available for this action.');
}


public function __construct(
    protected NotificationService $notificationService) {}
 
}

