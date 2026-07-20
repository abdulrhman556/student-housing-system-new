<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PaymentSettingService;

class PaymentSettingController extends Controller
{
    public function __construct(
        protected PaymentSettingService $paymentSettingService
    ) {
    }

    public function index()
    {
        return $this->paymentSettingService->index();
    }
}
