<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreAttendeeRequest
 * 
 * Purpose: Validates attendee registration data
 * Pattern: Form Request for automatic validation
 * Focus: Email uniqueness is critical for this system
 */
class StoreAttendeeRequest extends FormRequest
{
    /**
     * Authorization - allow anyone to register as attendee
     * This is a public registration endpoint
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for attendee registration
     * 
     * Rules explained:
     * - name: Required, text field (could add max length)
     * - email: 
     *   - required: Must provide email
     *   - email: Must be valid email format (user@domain.com)
    *   - unique:attendees,email: Email not already registered
     *     Could exclude current user in updates: unique:attendees,email,{id}
     * 
     * Security: Email validation prevents:
     * - Invalid email formats
     * - Duplicate registrations
     * - SQL injection (Laravel auto-escapes)
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|email|unique:attendees,email'
        ];
    }
}
