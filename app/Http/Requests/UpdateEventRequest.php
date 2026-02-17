<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * UpdateEventRequest
 * 
 * Purpose: Validates data when updating an existing event
 * Pattern: Separate request class from Store for flexibility
 * Why separate from StoreEventRequest?
 * - Updates can be partial (only send changed fields)
 * - Store requires all fields, Update makes all optional
 * - Better API design - PUT can update specific fields
 */
class UpdateEventRequest extends FormRequest
{
    /**
     * Authorization for updates
     * Currently open to all - could add ownership checks
     * Example: Only event creator can update
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for updating an event
     * 
     * Key difference from StoreEventRequest: 'sometimes' instead of 'required'
     * - 'sometimes': Only validate if field is present in request
     * - Allows partial updates: Can update just title, just date, etc.
     * - Client doesn't need to send all fields for every update
     * 
     * Rules remain same as create (same data types and constraints)
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'date' => 'sometimes|date',
            'country' => 'sometimes|string',
            'capacity' => 'sometimes|integer|min:1'
        ];
    }
}
