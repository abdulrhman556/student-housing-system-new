<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\Admin;
use App\Models\Unit;
use App\Models\Property;
use App\Models\Review;
use App\Models\BookingHistory;

class Booking extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'unit_id',
        'booking_date',
        'check_in_date',
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class, 'admin_id');
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

    public function property(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(
            Property::class,
            Unit::class,
            'id',
            'id',
            'unit_id',
            'property_id'
        );
    }
}
