<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // کۆکردنەوەی ئامارەکان بۆ کارتەکانی سەرەوە
        $stats = [
            'students_count' => Student::count(),
            'teachers_count' => Teacher::count(),
            'active_lessons_count' => Lesson::where('status', 'active')->count(),
            'total_paid_enrollments' => DB::table('lesson_student')->where('is_paid', 1)->count(),
        ];

        // هێنانی ٥ لە دوایین وانە کاراکان بۆ خشتەی سەرەکی
        $latestLessons = Lesson::with('teacher')
            ->where('status', 'active')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'latestLessons'));
    }
}
