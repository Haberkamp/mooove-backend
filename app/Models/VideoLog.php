<?php

namespace App\Models;

use Database\Factories\VideoLogFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['dance_class_id', 'name', 'video'])]
class VideoLog extends Model
{
    /** @use HasFactory<VideoLogFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<DanceClass, $this>
     */
    public function danceClass(): BelongsTo
    {
        return $this->belongsTo(DanceClass::class);
    }
}
