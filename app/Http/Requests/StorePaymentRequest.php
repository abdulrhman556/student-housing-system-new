<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'booking_id' => ['required', 'integer', 'exists:bookings,id'],
            'amount' => ['required', 'numeric', 'regex:/^\d{1,8}(\.\d{1,2})?$/', 'between:0.01,99999999.99'],
            'payment_method' => ['required', 'string', 'in:cash,card,transfer'],
            'reference_number' => ['nullable', 'string', 'max:255'],
            'payment_proof' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'booking_id.required' => 'The booking ID is required.',
            'booking_id.integer' => 'The booking ID must be an integer.',
            'booking_id.exists' => 'The selected booking does not exist.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be numeric.',
            'amount.regex' => 'The amount must be a valid decimal value with up to 2 decimal places.',
            'amount.between' => 'The amount must be between 0.01 and 99999999.99.',
            'payment_method.required' => 'The payment method is required.',
            'payment_method.in' => 'The selected payment method is invalid.',
            'payment_proof.nullable' => 'The payment proof may be empty.',
            'payment_proof.file' => 'The payment proof must be a valid file.',
            'payment_proof.image' => 'The payment proof must be an image file.',
            'payment_proof.mimes' => 'The payment proof must be a JPG, JPEG, or PNG file.',
            'payment_proof.max' => 'The payment proof may not be larger than 2MB.',
        ];
    }
}
