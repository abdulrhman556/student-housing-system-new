<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'booking_id.required' => 'The booking ID is required.',
            'booking_id.integer' => 'The booking ID must be an integer.',
            'booking_id.exists' => 'The selected booking does not exist.',
            'rating.required' => 'The rating is required.',
            'rating.integer' => 'The rating must be an integer.',
            'rating.between' => 'The rating must be between 1 and 5.',
            'comment.string' => 'The comment must be a string.',
            'comment.max' => 'The comment may not be greater than 1000 characters.',
        ];
    }
}
