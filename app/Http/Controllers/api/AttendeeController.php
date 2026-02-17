<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendee;
use Illuminate\Http\Request;

class AttendeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendees = Attendee::all();
        return response()->json($attendees);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:attendees,email',
        ]);

        $attendee = Attendee::create($validated);
        return response()->json($attendee, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Attendee $attendee)
    {
        return response()->json($attendee);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attendee $attendee)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:attendees,email,' . $attendee->id,
        ]);

        $attendee->update($validated);
        return response()->json($attendee);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attendee $attendee)
    {
        $id = $attendee->id;
        $attendee->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Attendee deleted successfully',
            'id' => $id
        ], 200);
    }
}
