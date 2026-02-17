<?php

namespace App\Repositories;

use App\Models\Attendee;
use App\Repositories\Contracts\AttendeeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Attendee data access implementation.
 */
class AttendeeRepository implements AttendeeRepositoryInterface
{
    /** Create an attendee. */
    public function create(array $data): Attendee
    {
        return Attendee::create($data);
    }

    /** Return all attendees. */
    public function index(): Collection
    {
        return Attendee::all();
    }

    /** Find attendee by id or fail. */
    public function find(int $id): Attendee
    {
        return Attendee::findOrFail($id);
    }

    /** Find attendee by email. */
    public function findByEmail(string $email): ?Attendee
    {
        return Attendee::where('email', $email)->first();
    }

    /** Update an attendee and return it. */
    public function update(Attendee $attendee, array $data): Attendee
    {
        $attendee->update($data);
        return $attendee;
    }

    /** Delete an attendee. */
    public function delete(Attendee $attendee): void
    {
        $attendee->delete();
    }
}