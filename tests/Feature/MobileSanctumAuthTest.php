<?php

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;

uses(RefreshDatabase::class);

test('a student can issue a mobile sanctum token', function () {
    $student = Student::factory()->create([
        'email' => 'student@example.com',
        'password' => 'password',
    ]);

    $response = $this->postJson('/api/sanctum/token', [
        'email' => $student->email,
        'password' => 'password',
        'device_name' => 'Test iPhone',
    ]);

    $response
        ->assertSuccessful()
        ->assertJsonPath('token_type', 'Bearer')
        ->assertJsonPath('student.email', $student->email)
        ->assertJsonMissingPath('student.password');

    $plainTextToken = $response->json('token');
    [$tokenId] = explode('|', $plainTextToken, 2);
    $token = PersonalAccessToken::find($tokenId);

    expect($plainTextToken)->toBeString()->toContain('|');
    expect($token)->not->toBeNull();
    expect($token?->tokenable_type)->toBe(Student::class);
    expect($token?->tokenable_id)->toBe($student->id);
});

test('sanctum authenticates api requests as a student', function () {
    $student = Student::factory()->create();
    $token = $student->createToken('Test Android')->plainTextToken;

    $response = $this
        ->withToken($token)
        ->getJson('/api/user');

    $response
        ->assertSuccessful()
        ->assertJsonPath('email', $student->email)
        ->assertJsonMissingPath('password');
});
