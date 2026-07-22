<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingStatusRequest extends FormRequest
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
            'status' => [
                'required',
                'string',
                'in:pending,contacting_owner,contacting_student,availability_confirmed,completed,cancelled',
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
            'status.required' => 'The status is required.',
            'status.string' => 'The status must be a string.',
            'status.in' => 'The selected status is invalid.',
            'admin_id.integer' => 'The admin ID must be an integer.',
            'admin_id.exists' => 'The selected admin does not exist.',
            'note.string' => 'The note must be a string.',
            'note.max' => 'The note may not be greater than 1000 characters.',
        ];
    }
}
