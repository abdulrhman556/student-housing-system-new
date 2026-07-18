<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Payment extends Model
{
    protected $fillable = [
        'booking_id',
        'amount',
        'payment_method',
        'reference_number',
        'payment_proof',
        'status',
        'verified_by',
        'verified_at',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // لو الأدمن عايز يشوف كل المدفوعات اللي راجعها.
    public function verifiedPayments()
    {
        return $this->hasMany(Payment::class, 'verified_by');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
