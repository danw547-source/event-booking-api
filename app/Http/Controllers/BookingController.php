<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Repositories\Contracts\BookingRepositoryInterface;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Thin booking API controller.
 * Uses service logic for booking rules and repository methods for retrieval.
 */
class BookingController extends Controller
{
    /**
     * Inject service + repository contracts.
     */
    public function __construct(private BookingService $bookingService, private BookingRepositoryInterface $bookingRepository) {}

    /** List bookings with optional filters. */
    public function index(Request $request): JsonResponse
    {
        return response()->json($this->bookingRepository->index($request->all()));
    }

    /** Return a single booking. */
    public function show(int $id): JsonResponse
    {
        return response()->json($this->bookingRepository->find($id));
    }

    /** Cancel a booking and return a confirmation message. */
    public function destroy(int $id): JsonResponse
    {
        $booking = $this->bookingRepository->find($id);
        $this->bookingRepository->delete($booking);

        return response()->json(['message' => 'Booking deleted successfully'], 200);
    }

    /** Create a booking and return 201. */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $booking = $this->bookingService->book(
            $validated['event_id'],
            $validated['attendee_id']
        );

        return response()->json($booking, 201);
    }
}
