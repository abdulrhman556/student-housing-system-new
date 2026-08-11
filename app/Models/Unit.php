<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'property_id',
        'unit_type',
        'title',
        'price',
        'capacity',
        'available_count',
        'gender',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * الوحدة تنتمي إلى عقار واحد
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * الوحدة لها حجوزات متعددة
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
