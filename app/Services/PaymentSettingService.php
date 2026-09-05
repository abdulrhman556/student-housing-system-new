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
}
