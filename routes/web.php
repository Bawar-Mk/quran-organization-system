<?php

use App\Http\Controllers\BackupController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\UserController;
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

    // ڕاوتەکانی خەزنکردن و هێنانەوەی تێمپلەیتی بڕوانامە
    Route::get('/certificate-presets', [LessonController::class, 'getPresets'])->name('presets.index');
    Route::post('/certificate-presets', [LessonController::class, 'storePreset'])->name('presets.store');
    Route::delete('/certificate-presets/{id}', [LessonController::class, 'destroyPreset'])->name('presets.destroy');
    // ڕاوتەکانی بەشی پاشەکەوتکردن (باکئەپ)
    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::post('/backup/create', [BackupController::class, 'createBackup'])->name('backup.create');
    Route::post('/backup/restore', [BackupController::class, 'restoreBackup'])->name('backup.restore');
    Route::get('/backup/download/{file}', [BackupController::class, 'download'])->name('backup.download');
    Route::post('/backup/open-folder', [BackupController::class, 'openFolder'])->name('backup.open_folder');

    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/{lesson}/take', [AttendanceController::class, 'take'])->name('attendances.take');
    Route::post('/attendances/{lesson}/ajax-store', [AttendanceController::class, 'ajaxStore'])->name('attendances.ajaxStore'); // ئەمەمان زیاد کرد
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('attendances.index');
    Route::get('/attendances/{lesson}/take', [AttendanceController::class, 'take'])->name('attendances.take');
    Route::post('/attendances/{lesson}', [AttendanceController::class, 'store'])->name('attendances.store');
    Route::get('/attendances/{lesson}/report', [AttendanceController::class, 'report'])->name('attendances.report');

    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');
    // ڕاوتی داشبۆرد بەستراوەتەوە بە کۆنتڕۆڵەرەکەی خۆیەوە بۆ هێنانی ئامارەکان
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::post('/finance/transaction', [FinanceController::class, 'storeTransaction'])->name('finance.storeTransaction'); // ئەمە
    Route::delete('/finance/transaction/{transaction}', [FinanceController::class, 'destroyTransaction'])->name('finance.destroyTransaction'); // وە ئەمە

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
