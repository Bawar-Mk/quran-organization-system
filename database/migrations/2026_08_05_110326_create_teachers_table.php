<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ١- سەرەتا خشتەی مامۆستایان دروست دەکەین
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('subjects')->nullable();
            $table->text('certificates')->nullable();
            $table->string('phone_number')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->text('address')->nullable();
            $table->date('join_date')->nullable();
            $table->string('experience')->nullable();
            $table->string('marital_status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // ٢- پاشان پەیوەندییەکە دەبەستین لەگەڵ خشتەی بەکارهێنەران (users)
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('teacher_id')->references('id')->on('teachers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        // لە کاتی سڕینەوە، سەرەتا پەیوەندییەکە دەپچڕێنین ئینجا خشتەکە دەسڕینەوە
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['teacher_id']);
        });

        Schema::dropIfExists('teachers');
    }
};
