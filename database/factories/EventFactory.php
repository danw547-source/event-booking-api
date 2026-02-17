<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory
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
        $eventTypes = [
            'Conference', 'Summit', 'Workshop', 'Meetup', 'Symposium', 
            'Festival', 'Expo', 'Seminar', 'Forum', 'Convention'
        ];
        
        $themes = [
            'Technology', 'Business', 'Innovation', 'Digital', 'Science',
            'Marketing', 'Design', 'Healthcare', 'Finance', 'Education',
            'AI', 'Sustainability', 'Leadership', 'Entrepreneurship'
        ];
        
        $descriptions = [
            'Join industry leaders and professionals for an immersive experience exploring the latest trends and innovations.',
            'Connect with experts and peers in this engaging gathering focused on advancing knowledge and best practices.',
            'Discover cutting-edge insights and network with innovators shaping the future of the industry.',
            'An essential gathering for professionals seeking to enhance their skills and expand their professional network.',
            'Experience transformative sessions led by renowned speakers and thought leaders from around the world.',
            'Participate in interactive sessions designed to inspire collaboration and drive meaningful change.',
            'Explore groundbreaking ideas and forge valuable connections with like-minded professionals.',
            'A premier event bringing together visionaries and practitioners to share expertise and insights.',
            'Engage in thought-provoking discussions and hands-on learning opportunities with industry experts.',
            'Unlock new opportunities and gain practical knowledge to advance your career and organization.'
        ];
        
        $year = fake()->dateTimeBetween('now', '+1 year')->format('Y');
        $eventType = fake()->randomElement($eventTypes);
        $theme = fake()->randomElement($themes);
        $description = fake()->randomElement($descriptions);
        
        return [
            'title' => "{$theme} {$eventType} {$year}",
            'description' => $description,
            'date' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'country' => fake()->country(),
            'capacity' => fake()->numberBetween(50, 500),
        ];
    }

    
}
