<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_id' => $this->booking_id,
            'student_id' => $this->student_id,
            'property_id' => $this->property_id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'student' => $this->whenLoaded('student', function () {
                return [
                    'id' => $this->student->id,
                    'name' => trim(($this->student->fname ?? '') . ' ' . ($this->student->lname ?? '')) ?: $this->student->email,
                ];
            }),
            'property' => $this->whenLoaded('property', function () {
                return [
                    'id' => $this->property->id,
                    'title' => $this->property->title,
                ];
            }),
        ];
    }
}
