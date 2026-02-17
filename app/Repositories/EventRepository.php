<?php

namespace App\Repositories;

use App\Models\Event;
use App\Repositories\Contracts\EventRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Event data access implementation.
 */
class EventRepository implements EventRepositoryInterface
{
    /** Return paginated events with optional filters. */
    public function index(array $filters): LengthAwarePaginator
    {
        $query = Event::query();
        
        // Apply country filter when provided.
        if (! empty($filters['country'])) {
            $query->where('country', $filters['country']);
        }

        return $query->paginate(10);
    }

    /** Find event by id or fail. */
    public function find(int $id): Event
    {
        return Event::findOrFail($id);
    }

    /** Create an event. */
    public function create(array $data): Event
    {
        return Event::create($data);
    }

    /** Update an event and return it. */
    public function update(Event $event, array $data): Event
    {
        $event->update($data);
        return $event;
    }

    /** Delete an event. */
    public function delete(Event $event): void
    {
        $event->delete();
    }
}
