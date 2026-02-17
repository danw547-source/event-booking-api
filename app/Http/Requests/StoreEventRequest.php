<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * StoreEventRequest
 * 
 * Purpose: Validates data when creating a new event
 * Pattern: Form Request validation - runs before controller action
 * Why: 
 * - Separates validation from controller (single responsibility)
 * - Automatic validation - Laravel stops request if validation fails
 * - Returns 422 with error details automatically
 * - Keeps controllers clean and focused
 */
class StoreEventRequest extends FormRequest
{
    /**
     * Authorization check
     * 
     * Return true: Anyone can create events (no authentication)
     * Could implement: Check if user is admin, has permission, etc.
     * Example: return auth()->check() && auth()->user()->isAdmin();
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating an event
     * 
     * Runs before controller receives request
     * All fields required except description (events can have no description)
     * 
     * Rules explained:
     * - title: Must exist, text, max 255 chars (database column limit)
     * - description: Optional (nullable), text field
     * - date: Must exist and be valid date format
     * - country: Must exist, text (could add validation for valid country codes)
     * - capacity: Must exist, integer, at least 1 person (business rule)
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'country' => 'required|string',
            'capacity' => 'required|int|min:1'
        ];
    }
}
