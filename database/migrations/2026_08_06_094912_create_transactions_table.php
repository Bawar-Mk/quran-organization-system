<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // ناوی مامەڵەکە (بۆ نموونە: قیستی وانەی قورئان، یان کڕینی لاپتۆپ)
            $table->enum('type', ['income', 'expense']); // جۆری مامەڵە: داهات یان خەرجی
            $table->decimal('amount', 15, 2); // بڕی پارەکە
            $table->date('transaction_date'); // بەرواری مامەڵەکە
            $table->text('notes')->nullable(); // تێبینی زیاتر
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // کام ئەدمین ئەمەی تۆمارکردووە
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
