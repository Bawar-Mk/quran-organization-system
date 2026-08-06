<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LessonController extends Controller
{
    public function index()
    {
        $activeLessons = Lesson::with('teacher')->where('status', 'active')->latest()->get();
        $finishedLessons = Lesson::with('teacher')->where('status', 'finished')->latest()->get();
        $teachers = Teacher::all();

        return view('lessons.index', compact('activeLessons', 'finishedLessons', 'teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days' => 'required|array|min:1', // وەرگرتنی ڕۆژەکان بە شێوەی ئەرەی
            'time' => 'required', // وەرگرتنی کاتژمێر
            'passing_score' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        // تێکەڵکردنی ڕۆژەکان و کاتەکە بۆ ناو یەک ستوون بۆ داتابەیسەکە
        $schedule = implode(' و ', $request->days) . ' - کاتژمێر ' . date('h:i A', strtotime($request->time));

        $validated['schedule'] = $schedule;
        unset($validated['days'], $validated['time']);

        Lesson::create($validated);
        return redirect()->route('lessons.index')->with('success', 'وانە بە سەرکەوتوویی دروستکرا.');
    }

    public function show(Lesson $lesson)
    {
        $students = $lesson->students()->orderByPivot('score', 'desc')->get();
        $allStudents = Student::whereNotIn('id', $students->pluck('id'))->get();

        return view('lessons.show', compact('lesson', 'students', 'allStudents'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days' => 'required|array|min:1',
            'time' => 'required',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        $schedule = implode(' و ', $request->days) . ' - کاتژمێر ' . date('h:i A', strtotime($request->time));

        $validated['schedule'] = $schedule;
        unset($validated['days'], $validated['time']);

        $lesson->update($validated);
        return back()->with('success', 'زانیارییەکانی وانەکە بە سەرکەوتوویی نوێکرایەوە.');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return redirect()->route('lessons.index')->with('success', 'وانەکە سڕایەوە.');
    }

    public function finish(Lesson $lesson)
    {
        $lesson->update(['status' => 'finished']);
        return back()->with('success', 'وانەکە بە سەرکەوتوویی کۆتایی پێهێنرا.');
    }

    public function enrollStudent(Request $request, Lesson $lesson)
    {
        $request->validate(['student_id' => 'required|exists:students,id', 'is_paid' => 'required|boolean']);
        $lesson->students()->attach($request->student_id, ['is_paid' => $request->is_paid]);
        return back()->with('success', 'خوێندکار بەشداریکرا.');
    }

    public function updateScore(Request $request, Lesson $lesson, Student $student)
    {
        $request->validate(['score' => 'required|integer|min:0|max:100']);
        $lesson->students()->updateExistingPivot($student->id, ['score' => $request->score]);
        return back()->with('success', 'نمرە نوێکرایەوە.');
    }

    public function removeStudent(Lesson $lesson, Student $student)
    {
        $lesson->students()->detach($student->id);
        return back()->with('success', 'خوێندکارەکە لە وانەکە سڕایەوە.');
    }

    public function printCertificate(Lesson $lesson, Student $student)
    {
        $enrollment = $lesson->students()->where('student_id', $student->id)->first();
        if (!$enrollment) {
            return redirect()->back()->with('error', 'ئەم خوێندکارە بەشدار نییە لەم خولەدا.');
        }
        if ($enrollment->pivot->score < $lesson->passing_score) {
            return redirect()->back()->with('error', 'ئەم خوێندکارە مافی وەرگرتنی بڕوانامەی نییە.');
        }
        return view('lessons.certificate', compact('lesson', 'student', 'enrollment'));
    }
}
