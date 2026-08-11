<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class University extends Model
{
    protected $fillable = [
        'city_id',
        'name',
    ];

    /**
     * الجامعة تنتمي إلى مدينة واحدة
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * الجامعة لها عقارات متعددة قريبة منها
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
