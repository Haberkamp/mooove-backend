<?php

namespace App\Models;

use Database\Factories\InstructorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

#[Fillable(['name', 'avatar'])]
class Instructor extends Model
{
    /** @use HasFactory<InstructorFactory> */
    use HasFactory;

    /**
     * @return HasMany<DanceCourse, $this>
     */
    public function danceCourses(): HasMany
    {
        return $this->hasMany(DanceCourse::class);
    }

    /**
     * @return HasManyThrough<DanceClass, DanceCourse, $this>
     */
    public function danceClasses(): HasManyThrough
    {
        return $this->hasManyThrough(DanceClass::class, DanceCourse::class);
    }
}
