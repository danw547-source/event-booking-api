<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Attendee;
use App\Models\Event;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BookingTest extends TestCase
{
    use DatabaseMigrations;

    public function test_attendee_can_book_event()
    {
        $event = Event::factory()->create(['capacity' => 1]);
        $attendee = Attendee::factory()->create();

        $response = $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id,
        ]);

        $response->assertStatus(201);
    }

    public function test_event_capacity_enforced()
    {
        $event = Event::factory()->create(['capacity' => 1]);
        $attendee1 = Attendee::factory()->create();
        $attendee2 = Attendee::factory()->create();

        // First booking should succeed
        $response1 = $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee1->id,
        ]);
        $response1->assertStatus(201);

        // Second booking should fail due to capacity
        $response2 = $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee2->id,
        ]);
        $response2->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Event is fully booked'
            ]);
    }

    public function test_attendee_cannot_double_book_event()
    {
        $event = Event::factory()->create(['capacity' => 2]);
        $attendee = Attendee::factory()->create();

        // First booking should succeed
        $response1 = $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id,
        ]);
        $response1->assertStatus(201);

        // Second booking should fail due to double booking
        $response2 = $this->postJson('/api/bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id,
        ]);
        $response2->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Duplicate booking'
            ]);
    }

    public function test_can_list_events()
    {
        Event::factory()->count(3)->create();

        $response = $this->getJson('/api/events');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data');
    }

    public function test_can_update_event()
    {
        $event = Event::factory()->create(['title' => 'Old Title']);

        $response = $this->putJson("/api/events/{$event->id}", [
            'title' => 'New Title',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'New Title']);
    }

    public function test_can_delete_event()
    {
        $event = Event::factory()->create();

        $response = $this->deleteJson("/api/events/{$event->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'id'])
            ->assertJson([
                'success' => true,
                'message' => 'Event deleted successfully',
                'id' => $event->id
            ]);
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_can_paginate_events()
    {
        \App\Models\Event::factory()->count(30)->create();

        $response = $this->getJson('/api/events?page=2');

        $response->assertStatus(200)
            ->assertJsonStructure(['data', 'links']);
    }
}
