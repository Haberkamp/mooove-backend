<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dance_course_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dance_course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['dance_course_id', 'student_id']);
        });

        Schema::table('dance_classes', function (Blueprint $table) {
            $table->foreignId('dance_course_id')->after('id')->constrained()->cascadeOnDelete();
            $table->dropConstrainedForeignId('instructor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dance_classes', function (Blueprint $table) {
            $table->foreignId('instructor_id')->after('id')->constrained()->cascadeOnDelete();
            $table->dropConstrainedForeignId('dance_course_id');
        });

        Schema::dropIfExists('dance_course_student');
    }
};
