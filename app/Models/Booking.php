<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Unit;
use App\Models\Property;
use App\Models\Review;
use App\Models\BookingHistory;

class Booking extends Model
{
    protected $fillable = [
        'student_id',
        'unit_id',
        'property_id',
        'status',
        'booking_date',
        'check_in_date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
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
