<?php

use App\Models\DanceClass;
use App\Models\DanceCourse;
use App\Models\Instructor;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

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
            'preview_url' => null,
            'preview_expires_at' => null,
        ]);
});

test('class detail returns a preview url when a preview image is stored', function () {
    Storage::fake('s3');

    $instructor = Instructor::factory()->create(['name' => 'Alex Teacher']);
    $course = DanceCourse::factory()->create(['instructor_id' => $instructor->id]);
    $previewPath = 'dance-classes/preview-images/preview.jpg';
    $danceClass = DanceClass::factory()->create([
        'dance_course_id' => $course->id,
        'title' => 'Hip Hop Basics',
        'spotify_url' => 'https://open.spotify.com/track/abc123',
        'preview_image' => $previewPath,
    ]);
    Storage::disk('s3')->put($previewPath, 'fake-image-bytes');

    $student = Student::factory()->create();
    $course->students()->attach($student);
    $token = $student->createToken('test')->plainTextToken;

    $response = $this->withToken($token)
        ->getJson("/api/classes/{$danceClass->id}");

    $response->assertSuccessful();
    $response->assertJsonPath('name', 'Hip Hop Basics');
    $response->assertJsonPath('instructor', 'Alex Teacher');
    $response->assertJsonPath('spotify_url', 'https://open.spotify.com/track/abc123');
    expect($response->json('preview_url'))->toBeString()->not->toBeEmpty();
    expect($response->json('preview_expires_at'))->toBeString()->not->toBeEmpty();
});
