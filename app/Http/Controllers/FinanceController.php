<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        $lessons = Lesson::all();

        // دروستکردنی کوێری بۆ هێنانی زانیارییەکان لە خشتەی ناوەندی (lesson_student)
        $query = DB::table('lesson_student')
            ->join('students', 'lesson_student.student_id', '=', 'students.id')
            ->join('lessons', 'lesson_student.lesson_id', '=', 'lessons.id')
            ->select(
                'students.full_name as student_name',
                'lessons.name as lesson_name',
                'lesson_student.is_paid',
                'lesson_student.created_at as enroll_date'
            );

        // فلتەرکردن بەپێی وانە
        if ($request->filled('lesson_id')) {
            $query->where('lessons.id', $request->lesson_id);
        }

        // فلتەرکردن بەپێی دۆخی پارەدان
        if ($request->filled('payment_status')) {
            if ($request->payment_status == 'paid') {
                $query->where('lesson_student.is_paid', 1);
            } elseif ($request->payment_status == 'unpaid') {
                $query->where('lesson_student.is_paid', 0);
            }
        }

        // هێنانی داتاکان بە شێوەی پاجینەیشن
        $transactions = $query->latest('lesson_student.created_at')->paginate(15)->appends($request->all());

        // ئامارە گشتییەکان بۆ سەرەوەی پەڕەکە
        $totalEnrolled = DB::table('lesson_student')->count();
        $totalPaid = DB::table('lesson_student')->where('is_paid', 1)->count();
        $totalUnpaid = DB::table('lesson_student')->where('is_paid', 0)->count();

        return view('finance.index', compact('transactions', 'lessons', 'totalEnrolled', 'totalPaid', 'totalUnpaid'));
    }
}
