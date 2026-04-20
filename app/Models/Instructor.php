<?php

namespace App\Models;

use Database\Factories\InstructorFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'avatar'])]
class Instructor extends Model
{
    /** @use HasFactory<InstructorFactory> */
    use HasFactory;
}
