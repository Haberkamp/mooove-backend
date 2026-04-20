<?php

namespace Database\Factories;

use App\Models\DanceCourse;
use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DanceCourse>
 */
class DanceCourseFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, true).' course',
            'instructor_id' => Instructor::factory(),
        ];
    }
}
