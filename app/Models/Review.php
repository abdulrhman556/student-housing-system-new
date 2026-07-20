<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'booking_id',
        'student_id',
        'property_id',
        'rating',
        'comment',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class,'student_id');
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
