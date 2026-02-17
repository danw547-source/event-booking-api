<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\BookingService; // This is the service class that contains the business logic for creating bookings. We will be testing this class directly in our unit tests.
use App\Repositories\EventRepository; // This repository will be used to interact with the Event model in our tests.
use App\Repositories\BookingRepository;
use App\Models\Event;
use App\Models\Attendee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Exception;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase; // This trait will automatically refresh the database after each test, ensuring a clean state for each test case.

    private BookingService $service;

    protected function setUp(): void
    {
        parent::setUp(); // Call the parent setup method to ensure the test environment is properly initialized.

        // Inject dependencies manually (simulating Laravel's container)
        $this->service = new BookingService(
            new EventRepository(),
            bookingRepository: new BookingRepository()
        );
    }

    public function test_can_book_event(): void
    {
        $event = Event::factory()->create(['capacity' => 1]);
        $attendee = Attendee::factory()->create();

        $booking = $this->service->book($event->id, $attendee->id);

        $this->assertDatabaseHas('bookings', [
            'event_id' => $event->id,
            'attendee_id' => $attendee->id
        ]);
    }


}
