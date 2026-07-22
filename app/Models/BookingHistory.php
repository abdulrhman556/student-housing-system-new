<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingHistory extends Model
{
    protected $table = 'booking_history';
    protected $fillable = [
        'booking_id',
        'admin_id',
        'status',
        'note',
    ];

    protected $casts = [
        'status' => 'string',
        'note' => 'string',
    ];


    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}
