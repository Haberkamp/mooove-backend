<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\IssueSanctumTokenRequest;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class MobileAuthController extends Controller
{
    public function store(IssueSanctumTokenRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        $student = Student::query()
            ->where('email', $credentials['email'])
            ->first();

        if (! $student || ! Hash::check($credentials['password'], $student->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $deviceName = 'mobile-'.Str::uuid()->toString();

        $token = $student->createToken($deviceName);

        return response()->json([
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'student' => $student,
        ]);
    }

    public function destroy(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();
        Auth::guard('sanctum')->forgetUser();

        return response()->noContent();
    }
}
