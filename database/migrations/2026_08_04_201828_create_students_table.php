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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('full_name'); // ١- ناوی تەواوی
            $table->string('gender'); // ٢- ڕەگەز
            $table->date('date_of_birth'); // ٣- بەرواری لە دایک بوون
            $table->string('education_level')->nullable(); // ٤- ئاستی خوێندن
            $table->string('phone_number')->nullable(); // ٥- ژمارەی مۆبایل
            $table->text('address')->nullable(); // ٦- ناونیشان
            $table->date('join_date'); // ٧- بەرواری پەیوەندی کردن
            $table->string('study_type')->default('ئاسایی'); // ٨- جۆری خوێندن (ئاسایی، ڕەوزە)
            $table->string('marital_status')->nullable(); // ١٠- باری خێزانداری
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
