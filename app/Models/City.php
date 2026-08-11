<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    protected $fillable = [
        'governorate_id',
        'name',
    ];

    /**
     * المدينة تنتمي إلى محافظة واحدة
     */
    public function governorate(): BelongsTo
    {
        return $this->belongsTo(Governorate::class);
    }

    /**
     * المدينة لها جامعات متعددة
     */
    public function universities(): HasMany
    {
        return $this->hasMany(University::class);
    }

    /**
     * المدينة لها عقارات متعددة
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
