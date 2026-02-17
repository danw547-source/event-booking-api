<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAttendeeRequest;
use App\Http\Requests\UpdateAttendeeRequest;
use App\Repositories\Contracts\AttendeeRepositoryInterface;
use App\Services\AttendeeService;
use Illuminate\Http\JsonResponse;

/**
 * Thin attendee API controller.
 * Delegates business rules to services and data access to repositories.
 */
class AttendeeController extends Controller
{
    /**
     * Inject service and repository contracts.
     */
    public function __construct(private AttendeeService $attendeeService, private AttendeeRepositoryInterface $attendeeRepository)
    {}

    /** List attendees. */
    public function index(): JsonResponse
    {
        return response()->json($this->attendeeRepository->index());
    }

    /** Register an attendee and return 201. */
    public function store(StoreAttendeeRequest $request):JsonResponse
    {
        $attendee = $this->attendeeService->register($request->validated());
        return response()->json($attendee, 201);
    }

    /** Return a single attendee. */
    public function show(int $id): JsonResponse
    {
        $attendee = $this->attendeeRepository->find($id);
        return response()->json($attendee);
    }

    /** Update attendee details. */
    public function update(UpdateAttendeeRequest $request, int $id):JsonResponse
    {
        $attendee = $this->attendeeRepository->find($id);
        return response()->json($this->attendeeService->update($attendee, $request->validated()));
    }

    /** Delete an attendee and return a confirmation message. */
    public function destroy(int $id): JsonResponse
    {
        $attendee = $this->attendeeRepository->find($id);
        $this->attendeeService->delete($attendee);

        return response()->json(['message' => 'Attendee deleted successfully'], 200);
    }

}
