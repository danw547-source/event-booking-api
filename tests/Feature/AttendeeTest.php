<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AttendeeTest extends TestCase
{
    use DatabaseMigrations;
    public function test_can_register_attendee()
    {
        $response = $this->postJson('/api/attendees', [
            'name' => 'Dan Wrigley',
            'email' => 'dan.wrigley@example.com'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['email' => 'dan.wrigley@example.com']);
    }

    public function test_can_get_attendee_details()
    {
        $attendee = \App\Models\Attendee::factory()->create();

        $response = $this->getJson("/api/attendees/{$attendee->id}");

        $response->assertStatus(200)
            ->assertJsonFragment(['email' => $attendee->email]);
    }


    public function test_can_get_all_attendee_details()
    {
        $attendees = \App\Models\Attendee::factory(20)->create();

        $response = $this->getJson("/api/attendees/");

        $response->assertStatus(200)
            ->assertJsonCount(20)
            ->assertJsonFragment(['email' => $attendees->first()->email]);
    }

    public function test_can_update_attendee_details()
    {
        $attendee = \App\Models\Attendee::factory()->create();

        $response = $this->putJson("/api/attendees/{$attendee->id}", [
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['name' => 'Updated Name']);
    }

    public function test_can_delete_attendee()
    {
        $attendee = \App\Models\Attendee::factory()->create();

        $response = $this->deleteJson("/api/attendees/{$attendee->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'message', 'id'])
            ->assertJson([
                'success' => true,
                'message' => 'Attendee deleted successfully',
                'id' => $attendee->id
            ]);
    }

    public function test_cannot_register_attendee_with_duplicate_email()
    {
        $attendee = \App\Models\Attendee::factory()->create([
            'email' => 'duplicate@example.com'
        ]);

        $response = $this->postJson('/api/attendees', [
            'name' => 'Another Attendee',
            'email' => 'duplicate@example.com'
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
                'message' => 'Duplicate email',
                'error' => 'An attendee with this email address already exists'
            ]);
    }
}
