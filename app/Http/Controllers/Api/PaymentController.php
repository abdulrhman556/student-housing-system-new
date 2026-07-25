<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Http\Requests\VerifyPaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {
    }

    /**
     * Upload payment.
     */
    public function store(StorePaymentRequest $request): JsonResponse
    {
        try {

            $studentId = Auth::id();

            if (!$studentId) {
                throw new RuntimeException('Unauthenticated.');
            }

            $payment = $this->paymentService->store(
                $request->validated(),
                $studentId
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment uploaded successfully.',
                'data' => new PaymentResource($payment),
            ], 201);

        } catch (RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Verify payment.
     */
    public function verify(
        Payment $payment,
        VerifyPaymentRequest $request
    ): JsonResponse
    {
        try {

            $adminId = Auth::id();

            if (!$adminId) {
                throw new RuntimeException('Unauthenticated.');
            }

            $payment = $this->paymentService->verify(
                $payment,
                $adminId,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully.',
                'data' => new PaymentResource($payment),
            ]);

        } catch (RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Reject payment.
     */
    public function reject(
        Payment $payment,
        VerifyPaymentRequest $request
    ): JsonResponse
    {
        try {

            $adminId = Auth::id();

            if (!$adminId) {
                throw new RuntimeException('Unauthenticated.');
            }

            $payment = $this->paymentService->reject(
                $payment,
                $adminId,
                $request->validated()
            );

            return response()->json([
                'success' => true,
                'message' => 'Payment rejected successfully.',
                'data' => new PaymentResource($payment),
            ]);

        } catch (RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * List booking payments.
     */
    public function index(Booking $booking): JsonResponse
    {
        try {

            $payments = $this->paymentService->index($booking);

            return response()->json([
                'success' => true,
                'data' => PaymentResource::collection($payments),
            ]);

        } catch (RuntimeException $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
