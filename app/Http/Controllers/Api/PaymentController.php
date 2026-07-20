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

class PaymentController extends Controller
{
    /**
     * Handle payment submission, verification, rejection, and listing.
     */
    public function __construct(protected PaymentService $paymentService)
    {
    }

    public function store(StorePaymentRequest $request): JsonResponse
    {
        try {
            $studentId = Auth::id() ?? 1;
            $payment = $this->paymentService->store($request->validated(), $studentId);

            return response()->json([
                'success' => true,
                'message' => 'Payment uploaded successfully.',
                'data' => new PaymentResource($payment),
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function verify(Payment $payment, VerifyPaymentRequest $request): JsonResponse
    {
        try {
            $adminId = Auth::id() ?? 1;
            $verifiedPayment = $this->paymentService->verify($payment, $adminId, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Payment verified successfully.',
                'data' => new PaymentResource($verifiedPayment),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function reject(Payment $payment, VerifyPaymentRequest $request): JsonResponse
    {
        try {
            $adminId = Auth::id() ?? 1;
            $rejectedPayment = $this->paymentService->reject($payment, $adminId, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Payment rejected successfully.',
                'data' => new PaymentResource($rejectedPayment),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function index(Booking $booking): JsonResponse
    {
        try {
            $payments = $this->paymentService->index($booking);

            return response()->json([
                'success' => true,
                'data' => PaymentResource::collection($payments),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
