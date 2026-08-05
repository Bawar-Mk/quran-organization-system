<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\FinanceController;
use Illuminate\Support\Facades\Route;

// هەرکەسێک هاتە ناو وێبسایتەکە، ڕاستەوخۆ بینێرە بۆ پەڕەی لۆگین
Route::get('/', function () {
    return redirect()->route('login');
});

// لێرەدا 'verified' مان لابرد چونکە ئیمەیڵمان پێویست نییە
Route::middleware(['auth'])->group(function () {

    // ڕاوتی داشبۆرد بەستراوەتەوە بە کۆنتڕۆڵەرەکەی خۆیەوە بۆ هێنانی ئامارەکان
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');

    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('lessons', LessonController::class);

    Route::patch('lessons/{lesson}/finish', [LessonController::class, 'finish'])->name('lessons.finish');
    Route::post('lessons/{lesson}/enroll', [LessonController::class, 'enrollStudent'])->name('lessons.enroll');
    Route::patch('lessons/{lesson}/students/{student}/score', [LessonController::class, 'updateScore'])->name('lessons.updateScore');
    Route::delete('lessons/{lesson}/students/{student}', [LessonController::class, 'removeStudent'])->name('lessons.removeStudent');
    Route::get('lessons/{lesson}/students/{student}/certificate', [LessonController::class, 'printCertificate'])->name('lessons.certificate');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
