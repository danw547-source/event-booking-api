<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return Booking::paginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'attendee_id' => 'required|exists:attendees,id',
        ]);

        $event= Event::find($validated['event_id']);

        if ($event->bookings()->count() >= $event->capacity) {
            return response()->json([
                'success' => false,
                'message' => 'Event is fully booked',
                'error' => 'This event has reached its maximum capacity and cannot accept more bookings'
            ], 422);
        }

        $exists = Booking::where($validated)->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Duplicate booking',
                'error' => 'This attendee is already booked for this event'
            ], 422);
        }

        $booking = Booking::create($validated);
        return response()->json($booking, 201);
    }

    public function show(Booking $booking)
    {
        return $booking;
    }

    public function destroy(Booking $booking)
    {
        $id = $booking->id;
        $booking->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully',
            'id' => $id
        ], 200);
    }
}
