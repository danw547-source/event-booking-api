<?php

namespace App\Services;

use App\Models\Attendee;
use App\Repositories\Contracts\AttendeeRepositoryInterface;

/**
 * AttendeeService
 * 
 * Purpose: Manages attendee (participant) business logic
 * Pattern: Service Layer for attendee-specific operations
 * Why: Centralizes duplicate email checking and attendee lifecycle management.
 * Technique applied: repository contract injection keeps the service independent
 * from concrete storage implementation.
 */
class AttendeeService
{
    /**
     * Repository injection for database operations
     */
    public function __construct(private AttendeeRepositoryInterface $attendeeRepository){}

    /**
     * Register a new attendee
     * 
     * Business Rule: Email must be unique across all attendees
     * Why check here when database has unique constraint?
     * - Provides descriptive error message for API consumers
     * - Database constraint is last line of defense
     * - Allows logging/monitoring of registration attempts
     */
    public function register(array $data): Attendee
    {
        if($this->attendeeRepository->findByEmail($data['email']))
        {
            throw new \DomainException('This email address is already registered.');
        }

        return $this->attendeeRepository->create($data);
    }

    /**
     * Update attendee information
     * Could add email change validation if needed (check not used in active bookings)
     */
    public function update(Attendee $attendee, array $data): Attendee
    {
        return $this->attendeeRepository->update($attendee, $data);
    }

    /**
     * Remove attendee from system
     * Cascade delete removes their bookings automatically
     */
    public function delete(Attendee $attendee):void
    {
        $this->attendeeRepository->delete($attendee);
    }
}
