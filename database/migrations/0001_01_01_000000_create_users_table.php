<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // یەکەم: دروستکردنی خشتەی مامۆستایان بۆ ئەوەی پێش بەکارهێنەر ئامادە بێت
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone_number')->nullable();
            $table->string('specialty')->nullable();
            $table->timestamps();
        });

        // دووەم: دروستکردنی خشتەی بەکارهێنەران و بەستنەوەی بە مامۆستاوە
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique(); // بەکارهێنانی یوزەرنەیم
            $table->string('password');
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->rememberToken();
            $table->timestamps();
        });

        // سێیەم: خشتە بنەڕەتییەکانی تری لاراڤێڵ
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('teachers');
    }
};
