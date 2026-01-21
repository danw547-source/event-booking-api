<?php

namespace Database\Seeders;

use App\Models\Attendee;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create events and attendees
        $events = Event::factory(12)->create();
        $attendees = Attendee::factory(40)->create();

        // Create specific scenario events
        $fullyBookedEvent = Event::factory()->create([
            'title' => 'Laravel Conference 2026',
            'capacity' => 20,
        ]);

        $upcomingEvent = Event::factory()->create([
            'title' => 'Tech Meetup',
            'date' => now()->addDays(30),
            'capacity' => 50,
        ]);

        // Create random bookings for general events (varying attendance)
        foreach ($events as $event) {
            // Skip creating bookings for event with ID 1
            if ($event->id === 1) {
                continue;
            }
            
            $bookingCount = rand(0, min($event->capacity, $attendees->count()));
            $selectedAttendees = $attendees->random($bookingCount);
            
            foreach ($selectedAttendees as $attendee) {
                Booking::create([
                    'event_id' => $event->id,
                    'attendee_id' => $attendee->id,
                ]);
            }
        }

        // Fully book the Laravel Conference (capacity = 20)
        $fullyBookedAttendees = $attendees->random(20);
        foreach ($fullyBookedAttendees as $attendee) {
            Booking::create([
                'event_id' => $fullyBookedEvent->id,
                'attendee_id' => $attendee->id,
            ]);
        }

        // Partially book the Tech Meetup (15 out of 50)
        $partialAttendees = $attendees->random(15);
        foreach ($partialAttendees as $attendee) {
            Booking::create([
                'event_id' => $upcomingEvent->id,
                'attendee_id' => $attendee->id,
            ]);
        }
    }
}
