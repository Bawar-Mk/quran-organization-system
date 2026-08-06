<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['ئامادە', 'نەهاتوو', 'مۆڵەت']);
            $table->text('notes')->nullable();
            $table->timestamps();

            // ڕێگریکردن لە تۆمارکردنی دوو ئامادەبوون بۆ یەک خوێندکار لە هەمان وانە و هەمان ڕۆژدا
            $table->unique(['lesson_id', 'student_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
