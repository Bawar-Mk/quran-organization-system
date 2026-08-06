<?php

namespace App\Http\Controllers;

use App\Models\CertificatePreset;
use Illuminate\Support\Facades\Storage;
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
            'days' => 'required|array|min:1',
            'time' => 'required',
            'passing_score' => 'required|integer|min:0|max:100',
            'certificate_template' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // زیادکراو
        ]);

        $schedule = implode(' و ', $request->days) . ' - کاتژمێر ' . date('h:i A', strtotime($request->time));
        $validated['schedule'] = $schedule;
        unset($validated['days'], $validated['time']);

        // سەیڤکردنی وێنەی تێمپلەیتەکە ئەگەر هەبوو
        if ($request->hasFile('certificate_template')) {
            $validated['certificate_template'] = $request->file('certificate_template')->store('certificates', 'public');
        }

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
            'certificate_template' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // زیادکراو
        ]);

        $schedule = implode(' و ', $request->days) . ' - کاتژمێر ' . date('h:i A', strtotime($request->time));
        $validated['schedule'] = $schedule;
        unset($validated['days'], $validated['time']);

        // سەیڤکردنی وێنەی نوێ و سڕینەوەی کۆنەکە
        if ($request->hasFile('certificate_template')) {
            if ($lesson->certificate_template) {
                Storage::disk('public')->delete($lesson->certificate_template);
            }
            $validated['certificate_template'] = $request->file('certificate_template')->store('certificates', 'public');
        }

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

    public function certificate(Lesson $lesson, Student $student)
    {
        $enrollment = DB::table('lesson_student')
            ->where('lesson_id', $lesson->id)
            ->where('student_id', $student->id)
            ->first();

        if (!$enrollment || $enrollment->score < $lesson->passing_score) {
            return redirect()->back()->with('error', 'ئەم خوێندکارە نمرەی دەرچوونی بەدەست نەهێناوە.');
        }

        // --- ئەم بەشە نوێیە بۆ خوێندنەوەی فۆنتەکان لە فۆڵدەری public/fonts ---
        $fonts = [];
        $fontsPath = public_path('fonts');
        if (\Illuminate\Support\Facades\File::exists($fontsPath)) {
            $files = \Illuminate\Support\Facades\File::files($fontsPath);
            foreach ($files as $file) {
                if (in_array($file->getExtension(), ['ttf', 'woff', 'woff2', 'otf'])) {
                    $fontName = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    $fonts[] = [
                        'name' => $fontName,
                        'file' => $file->getFilename()
                    ];
                }
            }
        }
        // ---------------------------------------------------------------------

        // زیادکردنی داتای فۆنتەکان بۆ ڤیووەکە
        return view('lessons.certificate', [
            'lesson' => $lesson,
            'student' => $student,
            'enrollment' => collect(['pivot' => $enrollment]),
            'availableFonts' => $fonts // ئەم گۆڕاوەمان بۆ زیاد کرد
        ]);
    }
    public function getPresets()
    {
        return response()->json(CertificatePreset::all());
    }

    public function storePreset(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'data' => 'required|array',
        ]);

        // بەکارهێنانی updateOrCreate بۆ ئەوەی ئەگەر هەمان ناو بوو، تەنها ئۆڤەڕایدی بکات
        $preset = CertificatePreset::updateOrCreate(
            ['name' => $request->name],
            ['data' => $request->data]
        );

        return response()->json(['success' => true]);
    }

    public function destroyPreset($id)
    {
        CertificatePreset::destroy($id);
        return response()->json(['success' => true]);
    }
}
