<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $eventTypes = ['Conference', 'Summit', 'Workshop', 'Seminar', 'Expo', 'Meetup', 'Forum', 'Bootcamp', 'Symposium', 'Webinar'];
        $topics = ['Business', 'Technology', 'Marketing', 'Leadership', 'Innovation', 'Digital', 'Strategy', 'Sales', 'Finance', 'Entrepreneurship', 'Design', 'Development'];
        
        $eventType = fake()->randomElement($eventTypes);
        $topic = fake()->randomElement($topics);
        $year = fake()->numberBetween(2026, 2027);
        
        $title = fake()->randomElement([
            "$topic $eventType $year",
            "Annual $topic $eventType",
            "$topic Leadership $eventType",
            "International $topic $eventType",
            "Global $topic Summit",
        ]);

        return [
            'title' => $title,
            'description' => fake()->paragraph(),
            'date' => fake()->dateTimeBetween('+1 days', '+1 year'),
            'country' => fake()->country(),
            'capacity' => fake()->numberBetween(50, 500),
        ];
    }
}
