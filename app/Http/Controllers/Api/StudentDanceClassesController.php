<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DanceClass;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentDanceClassesController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        /** @var Student $student */
        $student = $request->user();

        $rows = DanceClass::query()
            ->whereHas('danceCourse', function ($query) use ($student): void {
                $query->whereHas('students', function ($q) use ($student): void {
                    $q->where('students.id', $student->id);
                });
            })
            ->with(['danceCourse.instructor'])
            ->orderBy('title')
            ->get()
            ->map(fn (DanceClass $class) => array_merge(
                [
                    'id' => $class->id,
                    'name' => $class->title,
                    'author' => $class->danceCourse->instructor->name,
                ],
                $class->previewImageTemporaryUrlPayload() ?? [
                    'preview_url' => null,
                    'preview_expires_at' => null,
                ],
            ));

        return response()->json($rows);
    }
}
