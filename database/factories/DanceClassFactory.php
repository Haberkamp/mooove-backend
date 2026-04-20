<?php

namespace Database\Factories;

use App\Models\DanceClass;
use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DanceClass>
 */
class DanceClassFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'instructor_id' => Instructor::factory(),
            'video' => 'dance-classes/videos/'.fake()->uuid().'.mp4',
            'spotify_url' => 'https://open.spotify.com/track/'.fake()->bothify('??##########'),
        ];
    }
}
