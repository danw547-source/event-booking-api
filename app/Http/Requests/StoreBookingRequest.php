<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates booking creation payload.
 * Ensures referenced event and attendee records exist.
 */
class StoreBookingRequest extends FormRequest
{
    /** Allow public booking creation in this project version. */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validate required foreign keys.
     * Business checks (capacity, duplicate booking) are enforced in the service layer.
     */
    public function rules():array
    {
        return[
            'event_id' => 'required|exists:events,id',
            'attendee_id' => 'required|exists:attendees,id'
        ];
    }
}
