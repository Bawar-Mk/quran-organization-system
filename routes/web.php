<?php

use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// هەرکەسێک هاتە ناو وێبسایتەکە، ڕاستەوخۆ بینێرە بۆ پەڕەی لۆگین
Route::get('/', function () {
    return redirect()->route('login');
});

// لێرەدا 'verified' مان لابرد چونکە ئیمەیڵمان پێویست نییە
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('students', StudentController::class);
    Route::resource('teachers', TeacherController::class); // ڕاوتی مامۆستایان
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
