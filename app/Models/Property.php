<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'university_id',
        'city_id',
        'title',
        'description',
        'address',
        'latitude',
        'longitude',
        'status',
        'rejection_reason',
        'view_count',
        'is_featured',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    /**
     * علاقة العقار بصاحب السكن (User)
     */
    public function owner(): BelongsTo
    {
        // تم تحديد الـ foreign key هنا كـ owner_id لأن اسمه مختلف عن الافتراضي
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * علاقة العقار بالصور (علاقة رأس بأطراف - One to Many)
     */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    /**
     * ميزة إضافية لجلب الصورة الرئيسية للعقار مباشرة
     */
    public function coverImage()
    {
        return $this->hasOne(PropertyImage::class)->where('is_cover', true);
    }

    /**
     * علاقة العقار بالمرافق (أطراف بأطراف - Many to Many)
     * ملاحظة: هذه العلاقة تفترض وجود جدول وسيط باسم amenity_property
     */
public function amenities(): BelongsToMany
{
    // مررنا اسم الجدول الوسيط 'property_amenities' كبرامتر ثانٍ
    return $this->belongsToMany(Amenity::class, 'property_amenities');
}

}
