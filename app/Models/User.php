<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'fname',
        'lname',
        'email',
        'password',
        'phone',
        'gender',
        'profile_image',
        'role',
        'status',
        'national_id',
        'national_id_image',
        'university_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed', // شلنا التحقق من الإيميل لأنه مش موجود بالجدول
        ];
    }

    /**
     * 💡 ميزة إضافية مريحة جداً لـ تيم الفرونت إند:
     * Accessor يدمج الاسم الأول والأخير تلقائياً لعرض الاسم بالكامل
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->fname} {$this->lname}";
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'student_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'student_id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'student_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

}
