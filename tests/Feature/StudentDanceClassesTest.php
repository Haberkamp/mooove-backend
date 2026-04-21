<?php

use App\Models\DanceClass;
use App\Models\DanceCourse;
use App\Models\Instructor;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

test('classes endpoint requires authentication', function () {
    $this->getJson('/api/classes')->assertUnauthorized();
});

test('it returns name and author for each enrolled dance class', function () {
    $instructor = Instructor::factory()->create(['name' => 'Jane Instructor']);
    $course = DanceCourse::factory()->create(['instructor_id' => $instructor->id]);
    DanceClass::factory()->create([
        'dance_course_id' => $course->id,
        'title' => 'Morning Ballet',
    ]);
    $student = Student::factory()->create();
    $course->students()->attach($student);

    $token = $student->createToken('test')->plainTextToken;

    $response = $this->withToken($token)->getJson('/api/classes');

    $classId = DanceClass::query()->where('title', 'Morning Ballet')->value('id');

    $response
        ->assertSuccessful()
        ->assertExactJson([
            [
                'id' => $classId,
                'name' => 'Morning Ballet',
                'author' => 'Jane Instructor',
                'preview_url' => null,
                'preview_expires_at' => null,
            ],
        ]);
});

test('it does not return classes from courses the student is not enrolled in', function () {
    $instructor = Instructor::factory()->create();
    $enrolledCourse = DanceCourse::factory()->create(['instructor_id' => $instructor->id]);
    $otherCourse = DanceCourse::factory()->create(['instructor_id' => $instructor->id]);

    DanceClass::factory()->create([
        'dance_course_id' => $enrolledCourse->id,
        'title' => 'Included',
    ]);
    DanceClass::factory()->create([
        'dance_course_id' => $otherCourse->id,
        'title' => 'Excluded',
    ]);

    $student = Student::factory()->create();
    $enrolledCourse->students()->attach($student);

    $token = $student->createToken('test')->plainTextToken;

    $includedId = DanceClass::query()->where('title', 'Included')->value('id');

    $this->withToken($token)
        ->getJson('/api/classes')
        ->assertSuccessful()
        ->assertExactJson([
            [
                'id' => $includedId,
                'name' => 'Included',
                'author' => $instructor->name,
                'preview_url' => null,
                'preview_expires_at' => null,
            ],
        ]);
});

test('classes list returns preview url when a preview image is stored', function () {
    Storage::fake('s3');

    $instructor = Instructor::factory()->create(['name' => 'Jane Instructor']);
    $course = DanceCourse::factory()->create(['instructor_id' => $instructor->id]);
    $previewPath = 'dance-classes/preview-images/list-preview.jpg';
    DanceClass::factory()->create([
        'dance_course_id' => $course->id,
        'title' => 'Morning Ballet',
        'preview_image' => $previewPath,
    ]);
    Storage::disk('s3')->put($previewPath, 'fake-image-bytes');

    $student = Student::factory()->create();
    $course->students()->attach($student);
    $token = $student->createToken('test')->plainTextToken;

    $classId = DanceClass::query()->where('title', 'Morning Ballet')->value('id');

    $response = $this->withToken($token)->getJson('/api/classes');

    $response->assertSuccessful();
    $response->assertJsonPath('0.id', $classId);
    $response->assertJsonPath('0.name', 'Morning Ballet');
    $response->assertJsonPath('0.author', 'Jane Instructor');
    expect($response->json('0.preview_url'))->toBeString()->not->toBeEmpty();
    expect($response->json('0.preview_expires_at'))->toBeString()->not->toBeEmpty();
});
