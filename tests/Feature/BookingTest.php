<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_attendee_can_book_event(): void
    {
        // Arrange: Create event with a capity of 1, and a test attendee
        $event = Event::factory()->create(['capacity'=>1]);
        $attendee = Attendee::factory()->create([]);

        // Act: Send POST request to API
        $response = $this->postJson('/api/bookings',
        [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id
        ]);

        // Assert: API responds with HTTP 201 (Created)
        $response->assertStatus(201)
            ->assertJsonStructure([
                'id',
                'event_id',
                'attendee_id',
                'created_at',
                'updated_at'
            ]);

            // Also verfy the booking exists in the database
            $this->assertDatabaseHas('bookings',
            [
                'event_id' => $event->id,
                'attendee_id' => $attendee->id
            ]);
    }

    public function test_event_cannot_be_overbooked(): void{

        $event = Event::factory()->create(['capacity' => 1]);
        // Create 2 attendees so we can test overbooking
        $attendee = Attendee::factory(2)->create();

        // First booking succeeds
        $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee[0]->id
        ])->assertStatus(201);

        // Second booking fails
        $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee[1]->id
        
        ])->assertStatus(400)->assertJson(['error' => 'Event is full.']);

    }

    // Test that Attendee cannot book the same Event twice
    public function test_attendee_cannot_double_book():void
    {
        $event = Event::factory()->create();
        $attendee = Attendee::factory()->create();

        // First booking succeeds
        $this->postJson('/api/bookings/', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id
        ])->assertStatus(201);

        // Second booking fails
        $this->postJson('/api/bookings/', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id
        ])->assertStatus(400)->assertJson(['error' => 'Attendee already booked this event.']);
    }
}
