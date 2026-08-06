<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Lesson;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $stats = [
            'students_count' => 0,
            'teachers_count' => 0,
            'active_lessons_count' => 0,
            'total_paid_enrollments' => 0,
        ];

        $latestLessons = collect();

        // ئەگەر ئەدمین بوو، هەموو ئامارەکانی سیستەمەکەی پێ پیشان بدە
        if ($user->role === 'admin') {
            $stats['students_count'] = Student::count();
            $stats['teachers_count'] = Teacher::count();
            $stats['active_lessons_count'] = Lesson::where('status', 'active')->count();
            $stats['total_paid_enrollments'] = DB::table('lesson_student')->where('is_paid', 1)->count();

            $latestLessons = Lesson::with('teacher')
                ->where('status', 'active')
                ->latest()
                ->take(5)
                ->get();
        }
        // ئەگەر مامۆستا بوو، تەنها ئاماری خۆی پێ پیشان بدە بێ خشتە و پارەدان
        elseif ($user->role === 'teacher') {
            $teacherId = $user->teacher->id ?? 0;

            // ژمارەی خوێندکارەکانی خۆی بێ دووبارەبوونەوە
            if ($user->teacher) {
                $stats['students_count'] = $user->teacher->lessons()
                    ->with('students')
                    ->get()
                    ->pluck('students')
                    ->flatten()
                    ->unique('id')
                    ->count();
            }

            $stats['active_lessons_count'] = Lesson::where('teacher_id', $teacherId)->where('status', 'active')->count();

            // تێبینی: بەپێی داواکاریت کۆی پارەدانەکان و خشتەی چالاکییەکانمان بۆ مامۆستا لابرد
        }

        return view('dashboard', compact('stats', 'latestLessons', 'user'));
    }
}
