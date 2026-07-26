<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingHistory;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;
<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< HEAD
=======
use App\Services\NotificationService;
use App\Services\AdminAlertService;
>>>>>>> 75900001265cd670d550899471ab658b8245a496
=======
use App\Services\NotificationService;
use App\Services\AdminAlertService;
>>>>>>> Stashed changes
=======
use App\Services\NotificationService;
use App\Services\AdminAlertService;
>>>>>>> Stashed changes

class BookingService
{
    public function store(array $data): Booking
    {
        $studentId = $data['student_id'] ?? auth()->id() ?? 1;
        $unitId = $data['unit_id'] ?? null;
        $checkInDate = $data['check_in_date'] ?? null;

        if (! $unitId) {
            throw new InvalidArgumentException('The unit ID is required.');
        }

        if (! $checkInDate) {
            throw new InvalidArgumentException('The check-in date is required.');
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
                ->whereIn('status', ['pending', 'contacting_owner', 'contacting_student'])
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
    'history']);

        });
    }

    public function index()
    {
        return Booking::query()
->with([
    'student',
    'unit.property',
    'history'
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
        'history'
    ]);
}



    public function updateStatus(Booking|int $booking, string $status, array $data = []): Booking
    {
        $bookingModel = $this->resolveBooking($booking);

        if (! in_array($status, ['pending', 'availability_confirmed', 'completed', 'cancelled', 'rejected'], true)) {
            throw new InvalidArgumentException('Invalid booking status.');
        }

        if ($status === 'completed') {
            $paymentVerified = Payment::query()
                ->where('booking_id', $bookingModel->id)
                ->where('status', 'verified')
                ->exists();

            if (! $paymentVerified) {
                throw new RuntimeException('Booking cannot be completed until payment is verified.');
            }
        }

        return DB::transaction(function () use ($bookingModel, $status, $data): Booking {
            if ($bookingModel->status !== $status) {
                $bookingModel->status = $status;
                $bookingModel->save();

                if ($status === 'cancelled' && $bookingModel->status !== 'cancelled') {
                    $unit = Unit::query()->find($bookingModel->unit_id);

                    if ($unit) {
                        $unit->increment('available_count');
                    }
                }

                $this->createHistory($bookingModel, $data['admin_id'] ?? null, $status, $data['note'] ?? 'Booking status updated.');
                $this->notifyStudent($bookingModel, $status);
            }

            return $bookingModel->fresh(['student',  'unit.property', 'unit', 'history']);
        });
    }

    public function history(Booking|int $booking)
    {
        $bookingModel = $this->resolveBooking($booking);

        return $bookingModel->history()->latest()->get();
    }

    public function cancel(Booking|int $booking): Booking
    {
        $bookingModel = $this->resolveBooking($booking);

        $studentId = auth()->id();

        if ($studentId && (int) $bookingModel->student_id !== (int) $studentId) {
            throw new RuntimeException('You are not authorized to cancel this booking.');
        }

        return $this->updateStatus($bookingModel, 'cancelled', [
            'note' => 'Booking cancelled by student.',
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
        $resolvedAdminId = $this->resolveAdminId($adminId);

        return BookingHistory::query()->create([
            'booking_id' => $booking->id,
            'admin_id' => $resolvedAdminId,
            'status' => $status,
            'note' => $note,
        ]);
    }

<<<<<<< Updated upstream
<<<<<<< Updated upstream
<<<<<<< HEAD
    /**
     * Admin notifications are intentionally disabled because notifications.user_id
     * points to the users table while admins live in a separate admins table.
     * TODO: move this to the admin notification channel once supported by the architecture.
     */
    protected function notifyAdmin(Booking $booking, ?int $adminId = null): void
    {
        unset($booking, $adminId);
=======
    // protected function notifyAdmin(Booking $booking, ?int $adminId = null): Notification
    // {
    //     $resolvedAdminId = $this->resolveAdminId($adminId);

    //     return $this->notificationService->send([
    //         'user_id' => $resolvedAdminId,
    //         'type' => 'booking_created',
    //         'title' => 'New booking request',
    //         'body' => 'A new booking request has been created.',
    //         'data' => ['booking_id' => $booking->id],
    //     ]);
    // }


    protected function notifyAdmin(Booking $booking, ?int $adminId = null): void
    {
=======
    // protected function notifyAdmin(Booking $booking, ?int $adminId = null): Notification
    // {
    //     $resolvedAdminId = $this->resolveAdminId($adminId);

    //     return $this->notificationService->send([
    //         'user_id' => $resolvedAdminId,
    //         'type' => 'booking_created',
    //         'title' => 'New booking request',
    //         'body' => 'A new booking request has been created.',
    //         'data' => ['booking_id' => $booking->id],
    //     ]);
    // }


    protected function notifyAdmin(Booking $booking, ?int $adminId = null): void
    {
>>>>>>> Stashed changes
=======
    // protected function notifyAdmin(Booking $booking, ?int $adminId = null): Notification
    // {
    //     $resolvedAdminId = $this->resolveAdminId($adminId);

    //     return $this->notificationService->send([
    //         'user_id' => $resolvedAdminId,
    //         'type' => 'booking_created',
    //         'title' => 'New booking request',
    //         'body' => 'A new booking request has been created.',
    //         'data' => ['booking_id' => $booking->id],
    //     ]);
    // }


    protected function notifyAdmin(Booking $booking, ?int $adminId = null): void
    {
>>>>>>> Stashed changes
        try {
            app(AdminAlertService::class)
                ->newBooking($booking);
        } catch (\Throwable $e) {

            report($e);
        }
<<<<<<< Updated upstream
<<<<<<< Updated upstream
>>>>>>> 75900001265cd670d550899471ab658b8245a496
=======
>>>>>>> Stashed changes
=======
>>>>>>> Stashed changes
    }


    protected function notifyStudent(Booking $booking, string $status): Notification
    {
        return Notification::query()->create([
            'user_id' => $booking->student_id,
            'type' => 'booking_status_updated',
            'title' => 'Booking status updated',
            'body' => 'Your booking status has been updated to ' . $status . '.',
            'data' => ['booking_id' => $booking->id, 'status' => $status],
        ]);
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
