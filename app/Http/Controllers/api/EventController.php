<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        return Event::paginate();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'country' => 'required|string',
            'capacity' => 'required|integer|min:1',

        ]);

        return Event::create($validated);
    }

    public function show(Event $event)
    {
        return $event;
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'sometimes|required|date',
            'country' => 'sometimes|required|string',
            'capacity' => 'sometimes|required|integer|min:1',
        ]);

        $event->update($validated);
        return $event;
    }

    public function destroy(Event $event)
    {
        $id = $event->id;
        $event->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully',
            'id' => $id
        ], 200);
    }
}
