<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'payment_method',
        'account_name',
        'account_number',
        'qr_image',
        'is_active',
    ];
}
