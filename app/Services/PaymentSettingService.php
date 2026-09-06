<?php

namespace App\Services;

use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Cache;

class PaymentSettingService
{
    public function index()
    {
        return Cache::remember('payment_settings.active', now()->addHours(6), fn () => PaymentSetting::where('is_active', true)->get());
    }

    public function store(array $data): PaymentSetting
    {
        $setting = PaymentSetting::create([
            'payment_method' => $data['payment_method'],
            'account_name' => $data['account_name'],
            'account_number' => $data['account_number'],
            'qr_image' => $data['qr_image'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);

        Cache::forget('payment_settings.active');

        return $setting;
    }

    public function update(PaymentSetting $setting, array $data): PaymentSetting
    {
        $setting->update(array_filter([
            'payment_method' => $data['payment_method'] ?? null,
            'account_name' => $data['account_name'] ?? null,
            'account_number' => $data['account_number'] ?? null,
            'qr_image' => $data['qr_image'] ?? null,
            'is_active' => array_key_exists('is_active', $data) ? $data['is_active'] : null,
        ], fn ($value) => $value !== null));

        Cache::forget('payment_settings.active');

        return $setting->fresh();
    }
}
