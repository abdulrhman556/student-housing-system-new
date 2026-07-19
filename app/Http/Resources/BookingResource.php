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
            'property' => $this->whenLoaded('property', function () {
                return [
                    'id' => $this->property->id,
                    'title' => $this->property->title,
                ];
            }),
            'unit' => $this->whenLoaded('unit', function () {
                return [
                    'id' => $this->unit->id,
                    'type' => $this->unit->unit_type,
                    'price' => $this->unit->price,
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
