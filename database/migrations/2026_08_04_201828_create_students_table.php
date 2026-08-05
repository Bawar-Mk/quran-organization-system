<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // لێرە تەنها خشتەی students دروست دەکرێت
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('gender');
            $table->date('date_of_birth');
            $table->string('education_level')->nullable();
            $table->string('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->date('join_date');
            $table->string('study_type')->default('ئاسایی');
            $table->string('marital_status')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
