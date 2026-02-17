<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreEventRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Repositories\Contracts\EventRepositoryInterface;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Thin event API controller.
 * Keeps HTTP orchestration here and delegates business rules to services.
 */
class EventController extends Controller
{
    /**
     * Inject service for business operations and repository for simple reads.
     */
    public function __construct(
        private EventService $eventService,
        private EventRepositoryInterface $eventRepository
    ){}

    /** Get paginated events with optional filters. */
    public function index(Request $request) : JsonResponse
    {
        return response()->json($this->eventService->index($request->all()));
    }

    /** Create an event and return 201. */
    public function store(StoreEventRequest $request): JsonResponse
    {
        $event = $this->eventService->create($request->validated());
        return response()->json($event, 201);
    }

    /** Return a single event. */
    public function show(int $id): JsonResponse
    {
        return response()->json($this->eventRepository->find($id));
    }

    /** Update an event using partial validation rules. */
    public function update(UpdateEventRequest $request, int $id): JsonResponse
    {
        $event = $this->eventRepository->find($id);
        $updated = $this->eventService->update($event, $request->validated());

        return response()->json($updated);
    }

    /** Delete an event and return a confirmation message. */
    public function destroy(int $id): JsonResponse
    {
        $event = $this->eventRepository->find($id);
        $this->eventService->delete($event);

        return response()->json(['message' => 'Event deleted successfully'], 200);
    }
}
