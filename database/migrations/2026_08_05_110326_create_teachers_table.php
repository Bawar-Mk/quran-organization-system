<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('full_name'); // ١- ناوی تەواوی
            $table->string('subjects')->nullable(); // ٢- وانەکان (جارێ وەک تێکست)
            $table->text('certificates')->nullable(); // ٣- بڕوانامەکان
            $table->string('phone_number')->nullable(); // ٤- ژمارەی مۆبایل
            $table->date('date_of_birth')->nullable(); // ٥- بەرواری لەدایکبوون
            $table->text('address')->nullable(); // ٦- ناونیشان
            $table->date('join_date')->nullable(); // ٧- بەرواری پەیوەندی بە ڕێکخراو
            $table->string('experience')->nullable(); // ٨- ئەزموونی وانەوتنەوە
            $table->string('marital_status')->nullable(); // ٩- باری خێزانداری
            $table->text('notes')->nullable(); // ١٠- تێبینی
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
