<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use RuntimeException;

class PaymentService
{
    /**
     * Create a payment record and notify the admin for review.
     */
    public function __construct(
        protected NotificationService $notificationService,
        protected TelegramService $telegramService
    ) {
    }

    public function store(array $data, int $studentId): Payment
    {
        $booking = Booking::query()->findOrFail($data['booking_id']);

        if ((int) $booking->student_id !== $studentId) {
            throw new RuntimeException('You are not authorized to upload payment for this booking.');
        }

        if ($booking->status !== 'availability_confirmed') {
            throw new RuntimeException('Payment can only be uploaded when the booking is availability confirmed.');
        }

        $existingPendingOrVerifiedPayment = Payment::query()
            ->where('booking_id', $booking->id)
            ->whereIn('status', ['pending', 'verified'])
            ->exists();

        if ($existingPendingOrVerifiedPayment) {
            throw new RuntimeException('A payment is already pending or verified for this booking.');
        }

        $paymentProofPath = null;
        if (! empty($data['payment_proof'])) {
            $paymentProofPath = $data['payment_proof']->store('payments', 'public');
        }

        return DB::transaction(function () use ($booking, $data, $paymentProofPath): Payment {
            $payment = Payment::query()->create([
                'booking_id' => $booking->id,
                'amount' => $data['amount'],
                'payment_method' => $data['payment_method'],
                'reference_number' => $data['reference_number'],
                'payment_proof' => $paymentProofPath,
                'status' => 'pending',
            ]);

            $admins = User::where('role','admin')->get();
            foreach ($admins as $admin) {
                $this->notificationService->send([
                    'user_id' => $admin->id,
                    'type' => 'payment_uploaded',
                    'title' => 'New payment uploaded',
                    'body' => 'A new payment proof has been submitted for booking #' . $booking->id,
                    'data' => ['booking_id' => $booking->id, 'payment_id' => $payment->id],
                ]);

                $this->telegramService->send([
                    'chat_id' => config('services.telegram.chat_id'),
                    'message' => 'New payment uploaded for booking #' . $booking->id,
                ]);
            }

            return $payment->fresh(['booking']);
        });
    }

    public function verify(Payment $payment, int $adminId, array $data = []): Payment
    {
        if ($payment->status === 'verified') {
            throw new RuntimeException('This payment has already been verified.');
        }

        if ($payment->status === 'rejected') {
            throw new RuntimeException('This payment has already been rejected.');
        }

        return DB::transaction(function () use ($payment, $adminId, $data): Payment {
            $payment->update([
                'status' => 'verified',
                'verified_by' => $adminId,
                'verified_at' => now(),
            ]);

        $booking = $payment->booking;
            $booking->update([
                'status' => 'completed',
            ]);

            $this->notificationService->send([
                'user_id' => $payment->booking->student_id,
                'type' => 'payment_verified',
                'title' => 'Payment verified',
                'body' => 'Your payment has been verified. Your booking is now completed.',
                'data' => ['booking_id' => $payment->booking_id, 'payment_id' => $payment->id],
            ]);

            return $payment->fresh(['booking']);
        });
    }

    public function reject(Payment $payment, int $adminId, array $data = []): Payment
    {
        if ($payment->status === 'verified') {
            throw new RuntimeException('This payment has already been verified.');
        }

        if ($payment->status === 'rejected') {
            throw new RuntimeException('This payment has already been rejected.');
        }

        return DB::transaction(function () use ($payment, $adminId, $data): Payment {
            $payment->update([
                'status' => 'rejected',
                'verified_by' => $adminId,
                'verified_at' => now(),
            ]);

            $this->notificationService->send([
                'user_id' => $payment->booking->student_id,
                'type' => 'payment_rejected',
                'title' => 'Payment rejected',
                'body' => 'Your payment was rejected. You can upload another payment proof.',
                'data' => ['booking_id' => $payment->booking_id, 'payment_id' => $payment->id],
            ]);

            return $payment->fresh(['booking']);
        });
    }

    public function index(Booking $booking)
    {
        return Payment::query()
            ->where('booking_id', $booking->id)
            ->latest()
            ->get();
    }
}
