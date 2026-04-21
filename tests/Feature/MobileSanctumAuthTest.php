<?php

use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

test('a student can issue a mobile sanctum token', function () {
    $student = Student::factory()->create([
        'email' => 'student@example.com',
        'password' => 'password',
    ]);

    $response = $this->postJson('/api/sanctum/token', [
        'email' => $student->email,
        'password' => 'password',
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
    expect($token?->name)->toStartWith('mobile-');
});

test('sanctum authenticates api requests as a student', function () {
    $student = Student::factory()->create();

    Sanctum::actingAs($student, ['*']);

    $response = $this->getJson('/api/user');

    $response
        ->assertSuccessful()
        ->assertJsonPath('email', $student->email)
        ->assertJsonMissingPath('password');
});

test('a student can revoke the current sanctum token', function () {
    $student = Student::factory()->create();

    Sanctum::actingAs($student, ['*']);

    $this->postJson('/api/sanctum/logout')->assertNoContent();

    Auth::guard('sanctum')->forgetUser();

    $this->getJson('/api/user')->assertUnauthorized();
});

test('logout deletes the personal access token from the database', function () {
    $student = Student::factory()->create();
    $plainTextToken = $student->createToken('Test Device')->plainTextToken;
    [$tokenId] = explode('|', $plainTextToken, 2);

    $this->withToken($plainTextToken)
        ->postJson('/api/sanctum/logout')
        ->assertNoContent();

    expect(PersonalAccessToken::find($tokenId))->toBeNull();
    expect(PersonalAccessToken::findToken($plainTextToken))->toBeNull();
});
