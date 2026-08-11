<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'unit_id' => [
                'required',
                'integer',
                'exists:units,id',
            ],
            'check_in_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'admin_id' => [
                'nullable',
                'integer',
                'exists:admins,id',
            ],
            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'unit_id.required' => 'The unit ID is required.',
            'unit_id.integer' => 'The unit ID must be an integer.',
            'unit_id.exists' => 'The selected unit does not exist.',
            'check_in_date.required' => 'The check-in date is required.',
            'check_in_date.date' => 'The check-in date must be a valid date.',
            'check_in_date.after_or_equal' => 'The check-in date must be today or a future date.',
            'admin_id.integer' => 'The admin ID must be an integer.',
            'admin_id.exists' => 'The selected admin does not exist.',
            'note.string' => 'The note must be a string.',
            'note.max' => 'The note may not be greater than 1000 characters.',
        ];
    }
}
