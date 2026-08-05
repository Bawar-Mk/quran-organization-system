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
            'schedule' => 'required|string',
            'passing_score' => 'required|integer|min:0|max:100',
            'notes' => 'nullable|string',
        ]);

        Lesson::create($validated);
        return redirect()->route('lessons.index')->with('success', 'وانە بە سەرکەوتوویی دروستکرا.');
    }

    public function show(Lesson $lesson)
    {
        // هێنانی خوێندکاران و ڕیزبەندییان بەپێی نمرە
        $students = $lesson->students()->orderByPivot('score', 'desc')->get();
        $allStudents = Student::whereNotIn('id', $students->pluck('id'))->get(); // خوێندکارە بەشدارنەبووەکان

        return view('lessons.show', compact('lesson', 'students', 'allStudents'));
    }
    // فەنکشنی نوێکردنەوەی زانیاری وانە
    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'teacher_id' => 'required|exists:teachers,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'schedule' => 'required|string',
            'passing_score' => 'required|integer|min:0|max:100',
        ]);

        $lesson->update($validated);
        return back()->with('success', 'زانیارییەکانی وانەکە بە سەرکەوتوویی نوێکرایەوە.');
    }

    public function destroy(Lesson $lesson)
    {
        $lesson->delete();
        return redirect()->route('lessons.index')->with('success', 'وانەکە سڕایەوە.');
    }

    // کۆتایی هێنان بە وانە
    public function finish(Lesson $lesson)
    {
        $lesson->update(['status' => 'finished']);
        return back()->with('success', 'وانەکە بە سەرکەوتوویی کۆتایی پێهێنرا.');
    }

    // بەشداریکردنی خوێندکار لە خول
    public function enrollStudent(Request $request, Lesson $lesson)
    {
        $request->validate(['student_id' => 'required|exists:students,id', 'is_paid' => 'required|boolean']);
        $lesson->students()->attach($request->student_id, ['is_paid' => $request->is_paid]);
        return back()->with('success', 'خوێندکار بەشداریکرا.');
    }

    // نوێکردنەوەی نمرەی خوێندکار
    public function updateScore(Request $request, Lesson $lesson, Student $student)
    {
        $request->validate(['score' => 'required|integer|min:0|max:100']);
        $lesson->students()->updateExistingPivot($student->id, ['score' => $request->score]);
        return back()->with('success', 'نمرە نوێکرایەوە.');
    }

    // دەرکردنی خوێندکار لە خول
    public function removeStudent(Lesson $lesson, Student $student)
    {
        $lesson->students()->detach($student->id);
        return back()->with('success', 'خوێندکارەکە لە وانەکە سڕایەوە.');
    }
    // چاپکردنی بڕوانامە
    public function printCertificate(Lesson $lesson, Student $student)
    {
        // هێنانی زانیاری بەشداریکردنی ئەم خوێندکارە
        $enrollment = $lesson->students()->where('student_id', $student->id)->first();

        // دڵنیابوونەوە لەوەی کە خوێندکارەکە لە خولەکەدایە
        if (!$enrollment) {
            return redirect()->back()->with('error', 'ئەم خوێندکارە بەشدار نییە لەم خولەدا.');
        }

        // دڵنیابوونەوە لەوەی کە مەرجی نمرەی هێناوە
        if ($enrollment->pivot->score < $lesson->passing_score) {
            return redirect()->back()->with('error', 'ئەم خوێندکارە مافی وەرگرتنی بڕوانامەی نییە.');
        }

        return view('lessons.certificate', compact('lesson', 'student', 'enrollment'));
    }
}
