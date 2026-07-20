<?php

namespace App\Services;

use App\Models\PaymentSetting;

class PaymentSettingService
{
    public function index()
    {
        return PaymentSetting::where('is_active', true)->get();
    }
}
