<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    // use HasFactory
    protected $fillable = [
        'student_id',
        'unit_id',
        'status',
        'booking_date',
        'check_in_date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function history()
    {
        return $this->hasMany(BookingHistory::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
