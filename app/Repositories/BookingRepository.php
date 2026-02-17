<?php

namespace App\Repositories;

use App\Models\Booking;
use App\Repositories\Contracts\BookingRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Booking data access implementation.
 */
class BookingRepository implements BookingRepositoryInterface
{
    /** Return paginated bookings with eager-loaded event and attendee relations. */
    public function index(array $filters = []): LengthAwarePaginator
    {
        $query = Booking::with(['event', 'attendee']);

        // Optional attendee filter.
        if(!empty($filters['attendee_id']))
            {
                $query->where('attendee_id', $filters['attendee_id']);
            }
        
        return $query->paginate(10);
    }

    /** Find booking by id or fail. */
    public function find(int $id): Booking
    {
        return Booking::findOrFail($id);
    }

    /** Update a booking and return it. */
    public function update(Booking $booking, array $data): Booking
    {
        $booking->update($data);
        return $booking;
    }

    /** Check whether attendee is already booked for the event. */
    public function exists(int $eventId, int $attendeeId): bool
    {
        return Booking::where('event_id', $eventId)
        ->where('attendee_id', $attendeeId)
        ->exists();
    }

    /** Create a booking. */
    public function create(array $data):Booking
    {
        return Booking::create($data);
    }

    /** Delete a booking. */
    public function delete(Booking $booking): void
    {
        $booking->delete();
    }

}
