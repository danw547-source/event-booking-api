<?php

namespace App\Repositories\Contracts;

use App\Models\Attendee;
use Illuminate\Database\Eloquent\Collection;

/**
 * Contract used to narrow data-access dependencies to required behavior.
 * This improves testability and keeps consumers decoupled from implementation details.
 */
interface AttendeeRepositoryInterface
{
    public function create(array $data): Attendee;

    public function index(): Collection;

    public function find(int $id): Attendee;

    public function findByEmail(string $email): ?Attendee;

    public function update(Attendee $attendee, array $data): Attendee;

    public function delete(Attendee $attendee): void;
}
