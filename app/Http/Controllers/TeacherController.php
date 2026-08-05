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
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('phone_number', 'like', "%{$search}%")
                    ->orWhere('subjects', 'like', "%{$search}%");
            });
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
            'subjects' => 'nullable|string',
            'certificates' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'join_date' => 'nullable|date',
            'experience' => 'nullable|string',
            'marital_status' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        Teacher::create($validated);
        return redirect()->route('teachers.index')->with('success', 'مامۆستا بە سەرکەوتوویی زیادکرا.');
    }

    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);
        return view('teachers.show', compact('teacher'));
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'مامۆستا بە سەرکەوتوویی سڕایەوە.');
    }
}
