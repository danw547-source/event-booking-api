<?php

namespace App\Services;
 
use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Repositories\Contracts\EventRepositoryInterface;

/**
 * Encapsulates booking business rules.
 * Uses repository contracts for decoupled persistence access.
 */
class BookingService
{
    /** Inject event + booking repository contracts. */
    public function __construct(
        private EventRepositoryInterface $eventRepository,
        private BookingRepositoryInterface $bookingRepository
    ){}

    /**
     * Create a booking after capacity and duplicate checks.
     *
     * @throws \DomainException if the event is full or the attendee is already booked
     */
    public function book(int $eventId, int $attendeeId): Booking
    {
        // Fetch event to access capacity and relationships
        $event = $this->eventRepository->find($eventId);

        // Business Rule 1: Capacity checking
        if($event->bookings()->count() >= $event->capacity)
            {
                throw new \DomainException('Event is full.');
            }

        // Business Rule 2: Prevent duplicate bookings
        // Unique constraint in database is backup, this provides better error message
        if($this->bookingRepository->exists($eventId, $attendeeId))
            {
                throw new \DomainException('Attendee already booked this event.');
            }

            // All validations passed, create the booking
        return $this->bookingRepository->create([
            'event_id' => $eventId,
            'attendee_id' => $attendeeId
        ]);
    }
}
