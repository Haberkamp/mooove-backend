<?php

namespace Database\Factories;

use App\Models\DanceClass;
use App\Models\VideoLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<VideoLog>
 */
class VideoLogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dance_class_id' => DanceClass::factory(),
            'name' => fake()->words(3, true),
            'video' => 'dance-classes/video-logs/'.fake()->uuid().'.mp4',
        ];
    }
}
