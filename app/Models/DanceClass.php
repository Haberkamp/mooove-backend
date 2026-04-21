<?php

namespace App\Models;

use Database\Factories\DanceClassFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Support\Facades\Storage;

#[Fillable(['title', 'dance_course_id', 'video', 'preview_image', 'spotify_url'])]
class DanceClass extends Model
{
    /** @use HasFactory<DanceClassFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<DanceCourse, $this>
     */
    public function danceCourse(): BelongsTo
    {
        return $this->belongsTo(DanceCourse::class);
    }

    /**
     * @return HasOneThrough<Instructor, DanceCourse, $this>
     */
    public function instructor(): HasOneThrough
    {
        return $this->hasOneThrough(
            Instructor::class,
            DanceCourse::class,
            'id',
            'id',
            'dance_course_id',
            'instructor_id',
        );
    }

    /**
     * @return HasMany<VideoLog, $this>
     */
    public function videoLogs(): HasMany
    {
        return $this->hasMany(VideoLog::class);
    }

    /**
     * @return array{preview_url: string, preview_expires_at: string}|null
     */
    public function previewImageTemporaryUrlPayload(): ?array
    {
        $path = $this->preview_image;
        if ($path === null || $path === '') {
            return null;
        }

        $expires = now()->addHour();

        return [
            'preview_url' => Storage::disk('s3')->temporaryUrl($path, $expires),
            'preview_expires_at' => $expires->toIso8601String(),
        ];
    }
}
