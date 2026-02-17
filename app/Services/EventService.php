<?php

namespace App\Services;
use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * EventService
 * 
 * Purpose: Contains business rules for event management
 * Pattern: Service Layer - separates business logic from data access
 * Why: Keeps controllers thin and makes business rules testable and reusable.
 * Technique applied: the service depends on a repository contract (not implementation)
 * to follow Dependency Inversion and simplify mocking in tests.
 */
class EventService
{
    /**
     * Repository injection for data operations
     * Service orchestrates business rules, repository handles database
     */
    public function __construct(private EventRepositoryInterface $eventRepository){}

    /**
     * Get events with optional filtering
     * Delegates to repository - no business logic needed for listing
     */
    public function index(array $filters = []): LengthAwarePaginator
    {
        return $this->eventRepository->index($filters);
    }

    /**
     * Create a new event with validation
     * 
     * Business Rule: Capacity must be positive
     * Why validate here and not in form request?
     * - This is a business rule, not just format validation
    * - Prevents non-positive capacity even if called from other parts of the system
     * - Single source of truth for business rules
     */
    public function create(array $data): Event
    {
        if($data['capacity'] <= 0)
            {
                throw new \DomainException('Event capacity must be greater than zero.');
            }

        return $this->eventRepository->create($data);
    }

    /**
     * Update event details
     * Could add validation here if capacity changes affect existing bookings
     */
    public function update(Event $event, array $data): Event
    {
        return $this->eventRepository->update($event, $data);
    }

    /**
     * Delete an event
     * Cascade delete handles related bookings automatically via database constraints
     */
    public function delete(Event $event): void
    {
        $this->eventRepository->delete($event);
    }
}
