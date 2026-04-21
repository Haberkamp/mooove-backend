<?php

use App\Models\DanceClass;
use App\Models\DanceCourse;
use App\Models\Instructor;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('class detail endpoint requires authentication', function () {
    $danceClass = DanceClass::factory()->create();

    $this->getJson("/api/classes/{$danceClass->id}")->assertUnauthorized();
});

test('class detail returns 404 when the student is not enrolled', function () {
    $danceClass = DanceClass::factory()->create();
    $student = Student::factory()->create();
    $token = $student->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->getJson("/api/classes/{$danceClass->id}")
        ->assertNotFound();
});

test('class detail returns instructor, class name, spotify url, and empty video logs', function () {
    $instructor = Instructor::factory()->create(['name' => 'Alex Teacher']);
    $course = DanceCourse::factory()->create(['instructor_id' => $instructor->id]);
    $danceClass = DanceClass::factory()->create([
        'dance_course_id' => $course->id,
        'title' => 'Hip Hop Basics',
        'spotify_url' => 'https://open.spotify.com/track/abc123',
    ]);
    $student = Student::factory()->create();
    $course->students()->attach($student);
    $token = $student->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->getJson("/api/classes/{$danceClass->id}")
        ->assertSuccessful()
        ->assertExactJson([
            'name' => 'Hip Hop Basics',
            'instructor' => 'Alex Teacher',
            'spotify_url' => 'https://open.spotify.com/track/abc123',
            'video_logs' => [],
        ]);
});
