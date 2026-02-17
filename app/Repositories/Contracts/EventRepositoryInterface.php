<?php

namespace App\Repositories\Contracts;

use App\Models\Event;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Contract used to apply Dependency Inversion Principle.
 * High-level services/controllers depend on this abstraction, not a concrete repository.
 */
interface EventRepositoryInterface
{
    public function index(array $filters): LengthAwarePaginator;

    public function find(int $id): Event;

    public function create(array $data): Event;

    public function update(Event $event, array $data): Event;

    public function delete(Event $event): void;
}
