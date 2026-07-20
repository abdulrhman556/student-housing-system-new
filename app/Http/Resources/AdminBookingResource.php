<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminBookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'booking_date' => $this->booking_date,
            'check_in_date' => $this->check_in_date,
            'student' => [
                'id' => $this->student?->id,
                'name' => trim(($this->student?->fname ?? '') . ' ' . ($this->student?->lname ?? '')) ?: $this->student?->email,
            ],
            'property' => [
                'id' => $this->property?->id,
                'title' => $this->property?->title,
            ],
            'unit' => [
                'id' => $this->unit?->id,
                'type' => $this->unit?->unit_type,
                'available_count' => $this->unit?->available_count,
            ],
        ];
    }
}
