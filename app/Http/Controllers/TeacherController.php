<?php

namespace App\Http\Controllers;

use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $query = Teacher::query();

        if ($request->filled('search')) {
            $query->where('full_name', 'like', '%' . $request->search . '%')
                ->orWhere('phone_number', 'like', '%' . $request->search . '%')
                ->orWhere('subjects', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('marital_status') && $request->marital_status !== 'هەردووکی') {
            $query->where('marital_status', $request->marital_status);
        }

        $teachers = $query->latest()->paginate(10)->appends($request->all());

        return view('teachers.index', compact('teachers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'marital_status' => 'nullable|string',
            'join_date' => 'nullable|date',
            'experience' => 'nullable|string',
            'subjects' => 'nullable|string',
            'certificates' => 'nullable|string',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Teacher::create($validated);
        return redirect()->route('teachers.index')->with('success', 'مامۆستا بە سەرکەوتوویی تۆمارکرا.');
    }

    public function show(Request $request, Teacher $teacher)
    {
        $query = $teacher->lessons()->withCount('students');

        if ($request->filled('lesson_name')) {
            $query->where('name', 'like', '%' . $request->lesson_name . '%');
        }

        $lessons = $query->latest('start_date')->paginate(10)->appends($request->all());

        return view('teachers.show', compact('teacher', 'lessons'));
    }

    // فەنکشنی سەیڤکردنی گۆڕانکارییەکان لە ڕێگەی مۆدڵەکەوە
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone_number' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'marital_status' => 'nullable|string',
            'join_date' => 'nullable|date',
            'experience' => 'nullable|string',
            'subjects' => 'nullable|string',
            'certificates' => 'nullable|string',
            'address' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $teacher->update($validated);
        return redirect()->route('teachers.index')->with('success', 'زانیارییەکانی مامۆستا بە سەرکەوتوویی نوێکرایەوە.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'مامۆستاکە بە سەرکەوتوویی سڕایەوە.');
    }
}
