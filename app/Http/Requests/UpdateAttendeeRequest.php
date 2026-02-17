<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateAttendeeRequest
 *
 * Technique: dedicated request object for updates.
 * Why applied: it keeps create/update validation rules independent and prevents
 * false-positive unique email failures when an attendee keeps their current email.
 */
class UpdateAttendeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:attendees,email,' . $this->route('attendee'),
        ];
    }
}
