<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DanceClass;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentDanceClassDetailController extends Controller
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

        $danceClass->loadMissing('danceCourse.instructor');

        return response()->json(array_merge(
            [
                'name' => $danceClass->title,
                'instructor' => $danceClass->danceCourse->instructor->name,
                'spotify_url' => $danceClass->spotify_url,
                'video_logs' => [],
            ],
            $danceClass->previewImageTemporaryUrlPayload() ?? [
                'preview_url' => null,
                'preview_expires_at' => null,
            ],
        ));
    }
}
