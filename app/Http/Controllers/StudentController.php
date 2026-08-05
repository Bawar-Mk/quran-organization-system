<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::query();

        if ($request->filled('search')) {
            $query->where('full_name', 'like', '%' . $request->search . '%')
                ->orWhere('phone_number', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('study_type') && $request->study_type !== 'هەمووی') {
            $query->where('study_type', $request->study_type);
        }

        $students = $query->latest()->paginate(10)->appends($request->all());

        return view('students.index', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'education_level' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'join_date' => 'required|date',
            'study_type' => 'required|string',
            'marital_status' => 'nullable|string',
        ]);

        Student::create($validated);
        return redirect()->route('students.index')->with('success', 'خوێندکار بە سەرکەوتوویی تۆمارکرا.');
    }

    public function show(Student $student)
    {
        // هێنانی وانەکانی خوێندکار بە پێی کاتی بەشداریکردن
        $lessons = $student->lessons()->with('teacher')->latest('pivot_created_at')->get();

        return view('students.show', compact('student', 'lessons'));
    }
    // فەنکشنی نوێکردنەوەی زانیاری خوێندکار
    public function update(Request $request, Student $student)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date',
            'education_level' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'join_date' => 'required|date',
            'study_type' => 'required|string',
            'marital_status' => 'nullable|string',
        ]);

        $student->update($validated);
        return redirect()->route('students.index')->with('success', 'زانیارییەکانی خوێندکارەکە بە سەرکەوتوویی نوێکرایەوە.');
    }

    public function destroy(Student $student)
    {
        $student->delete();
        return redirect()->route('students.index')->with('success', 'خوێندکارەکە بە سەرکەوتوویی سڕایەوە.');
    }
}
