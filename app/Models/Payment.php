<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Admin;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'reference_number',
        'payment_proof',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function verifier()
    {
        return $this->belongsTo(Admin::class, 'verified_by');
    }
}
