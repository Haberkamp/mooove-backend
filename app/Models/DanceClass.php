<?php

namespace App\Models;

use Database\Factories\DanceClassFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

#[Fillable(['title', 'dance_course_id', 'video', 'spotify_url'])]
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
}
