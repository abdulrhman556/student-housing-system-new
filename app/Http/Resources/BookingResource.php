<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'booking_date' => $this->booking_date,
            'check_in_date' => $this->check_in_date,
            'student' => $this->whenLoaded('student', function () {
                return [
                    'id' => $this->student->id,
                    'name' => trim(($this->student->fname ?? '') . ' ' . ($this->student->lname ?? '')) ?: $this->student->email,
                    'email' => $this->student->email,
                ];
            }),
            'property' => $this->whenLoaded('unit', function () {
                return $this->unit && $this->unit->property ? [
                    'id' => $this->unit->property->id,
                    'title' => $this->unit->property->title,
                ] : null;
}),

                'unit' => $this->whenLoaded('unit', function () {
                return [
                    'id' => $this->unit->id,
                    'type' => $this->unit->unit_type,
                    'title' => $this->unit->title,
                    'price' => $this->unit->price,
                    'capacity' => $this->unit->capacity,
                    'available_count' => $this->unit->available_count,
                ];
            }),
            'history' => $this->whenLoaded('history', function () {
                return $this->history->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'status' => $item->status,
                        'note' => $item->note,
                        'created_at' => $item->created_at,
                    ];
                });
            }),
        ];
    }
}
