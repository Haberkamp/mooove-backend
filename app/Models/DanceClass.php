<?php

namespace App\Models;

use Database\Factories\DanceClassFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title', 'instructor_id', 'video', 'spotify_url'])]
class DanceClass extends Model
{
    /** @use HasFactory<DanceClassFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Instructor, $this>
     */
    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * @return HasMany<VideoLog, $this>
     */
    public function videoLogs(): HasMany
    {
        return $this->hasMany(VideoLog::class);
    }
}
