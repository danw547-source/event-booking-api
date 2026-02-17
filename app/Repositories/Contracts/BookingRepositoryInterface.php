<?php

namespace App\Repositories\Contracts;

use App\Models\Booking;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Contract for booking persistence operations.
 * Applied so business logic depends on abstractions and can be swapped/mocked safely.
 */
interface BookingRepositoryInterface
{
    public function index(array $filters = []): LengthAwarePaginator;

    public function find(int $id): Booking;

    public function update(Booking $booking, array $data): Booking;

    public function exists(int $eventId, int $attendeeId): bool;

    public function create(array $data): Booking;

    public function delete(Booking $booking): void;
}
