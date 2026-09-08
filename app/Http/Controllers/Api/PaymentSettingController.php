<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use App\Services\PaymentSettingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    public function __construct(
        protected PaymentSettingService $paymentSettingService
    ) {
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->paymentSettingService->index(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'payment_method' => 'required|in:instapay,vodafone_cash,bank_transfer,fawry',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'qr_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = $request->only(['payment_method', 'account_name', 'account_number', 'is_active']);

        if ($request->hasFile('qr_image')) {
            $data['qr_image'] = $request->file('qr_image')->store('payment_settings', 'public');
        }

        $setting = $this->paymentSettingService->store($data);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة حساب استلام الدفع بنجاح',
            'data' => $setting,
        ], 201);
    }

    public function update(Request $request, PaymentSetting $paymentSetting): JsonResponse
    {
        $request->validate([
            'payment_method' => 'sometimes|in:instapay,vodafone_cash,bank_transfer,fawry',
            'account_name' => 'sometimes|string|max:255',
            'account_number' => 'sometimes|string|max:255',
            'qr_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'is_active' => 'sometimes|boolean',
        ]);

        $data = $request->only(['payment_method', 'account_name', 'account_number', 'is_active']);

        if ($request->hasFile('qr_image')) {
            $data['qr_image'] = $request->file('qr_image')->store('payment_settings', 'public');
        }

        $setting = $this->paymentSettingService->update($paymentSetting, $data);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث بيانات حساب الاستلام بنجاح',
            'data' => $setting,
        ]);
    }
}
