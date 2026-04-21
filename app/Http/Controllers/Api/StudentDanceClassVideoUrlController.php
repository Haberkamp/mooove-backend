<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DanceClass;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StudentDanceClassVideoUrlController extends Controller
{
    public function __invoke(Request $request, DanceClass $danceClass): JsonResponse
    {
        /** @var Student $student */
        $student = $request->user();

        $enrolled = $danceClass->danceCourse
            ->students()
            ->where('students.id', $student->id)
            ->exists();

        abort_unless($enrolled, 404);

        $path = $danceClass->video;
        if ($path === null || $path === '') {
            abort(404);
        }

        $expires = now()->addHour();

        $url = Storage::disk('s3')->temporaryUrl($path, $expires);

        return response()->json([
            'url' => $url,
            'expires_at' => $expires->toIso8601String(),
        ]);
    }
}
