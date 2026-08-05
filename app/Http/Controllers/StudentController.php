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
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender') && $request->gender !== 'هەردووکی') {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('marital_status') && $request->marital_status !== 'هەردووکی') {
            $query->where('marital_status', $request->marital_status);
        }

        if ($request->filled('study_type') && $request->study_type !== 'هەردووکی') {
            $query->where('study_type', $request->study_type);
        }

        if ($request->filled('date_of_birth')) {
            $query->whereDate('date_of_birth', $request->date_of_birth);
        }

        if ($request->filled('join_date')) {
            $query->whereDate('join_date', $request->join_date);
        }

        if ($request->filled('education_level')) {
            $query->where('education_level', 'like', "%{$request->education_level}%");
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
        return redirect()->route('students.index')->with('success', 'خوێندکار بە سەرکەوتوویی زیادکرا.');
    }

    // پیشاندانی زانیاری خوێندکار
    public function show($id)
    {
        $student = Student::findOrFail($id);
        // لێرەدا لە داهاتوودا وانەکان و خولەکان دەنێریت بۆ ڤیوەکە
        return view('students.show', compact('student'));
    }

    // سڕینەوەی خوێندکار
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
        return redirect()->route('students.index')->with('success', 'خوێندکار بە سەرکەوتوویی سڕایەوە.');
    }
}
