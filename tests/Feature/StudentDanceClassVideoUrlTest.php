<?php

use App\Models\DanceClass;
use App\Models\DanceCourse;
use App\Models\Instructor;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('video url endpoint requires authentication', function () {
    $danceClass = DanceClass::factory()->create();

    $this->getJson("/api/classes/{$danceClass->id}/video-url")->assertUnauthorized();
});

test('video url returns 404 when the student is not enrolled', function () {
    $danceClass = DanceClass::factory()->create();
    $student = Student::factory()->create();
    $token = $student->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->getJson("/api/classes/{$danceClass->id}/video-url")
        ->assertNotFound();
});

test('video url returns 404 when the class has no video path', function () {
    Storage::fake('s3');

    $instructor = Instructor::factory()->create();
    $course = DanceCourse::factory()->create(['instructor_id' => $instructor->id]);
    $danceClass = DanceClass::factory()->create([
        'dance_course_id' => $course->id,
        'video' => '',
    ]);
    $student = Student::factory()->create();
    $course->students()->attach($student);
    $token = $student->createToken('test')->plainTextToken;

    $this->withToken($token)
        ->getJson("/api/classes/{$danceClass->id}/video-url")
        ->assertNotFound();
});

test('video url returns a temporary url for enrolled students', function () {
    Storage::fake('s3');

    $instructor = Instructor::factory()->create();
    $course = DanceCourse::factory()->create(['instructor_id' => $instructor->id]);
    $videoPath = 'dance-classes/videos/test-video.mp4';
    $danceClass = DanceClass::factory()->create([
        'dance_course_id' => $course->id,
        'video' => $videoPath,
    ]);
    Storage::disk('s3')->put($videoPath, 'fake-video-bytes');

    $student = Student::factory()->create();
    $course->students()->attach($student);
    $token = $student->createToken('test')->plainTextToken;

    $response = $this->withToken($token)
        ->getJson("/api/classes/{$danceClass->id}/video-url");

    $response->assertSuccessful();
    $response->assertJsonStructure(['url', 'expires_at']);
    expect($response->json('url'))->toBeString()->not->toBeEmpty();
    expect($response->json('expires_at'))->toBeString()->not->toBeEmpty();
});
