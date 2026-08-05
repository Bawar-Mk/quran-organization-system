<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // خشتەی وانەکان
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ناوی وانە
            $table->foreignId('teacher_id')->constrained('teachers')->cascadeOnDelete(); // مامۆستای وانە
            $table->date('start_date'); // دەستپێک
            $table->date('end_date'); // کۆتایی
            $table->string('schedule'); // کاتی وانە
            $table->integer('passing_score'); // نمرەی وەرگرتنی بڕوانامە
            $table->string('status')->default('active'); // دۆخ: active (بەردەوامە) یان finished (کۆتایی هاتووە)
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // خشتەی پەیوەندی نێوان خوێندکار و وانە (بۆ نمرە و پارەدان)
        Schema::create('lesson_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_paid')->default(false); // دۆخی پارەدان
            $table->integer('score')->nullable(); // کۆنمرە
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lesson_student');
        Schema::dropIfExists('lessons');
    }
};
