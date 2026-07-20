<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'image',
        'is_cover',
        'display_order',
        'public_id',
    ];

    protected $casts = [
        'is_cover' => 'boolean',
        'display_order' => 'integer',
    ];

    /**
     * الصورة تنتمي لعقار واحد فقط
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
