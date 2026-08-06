<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $query = Lesson::with('teacher')->where('status', 'active');

        if ($user->role === 'teacher') {
            $query->where('teacher_id', $user->teacher->id ?? 0);
        }

        $lessons = $query->latest()->get();

        return view('attendances.index', compact('lessons'));
    }

    public function take(Lesson $lesson)
    {
        $user = Auth::user();
        if ($user->role === 'teacher' && $lesson->teacher_id !== ($user->teacher->id ?? 0)) {
            abort(403, 'تۆ ناتوانیت ئامادەبوونی وانەی مامۆستایەکی تر وەربگریت.');
        }

        $students = $lesson->students;

        $scheduleStr = $lesson->schedule;
        $foundDays = [];

        $scheduleStr = str_replace(
            ['1', '2', '3', '4', '5', '١', '٢', '٣', '٤', '٥'],
            ['یەک', 'دوو', 'سێ', 'چوار', 'پێنج', 'یەک', 'دوو', 'سێ', 'چوار', 'پێنج'],
            $scheduleStr
        );

        $daysMap = [
            'یەکشەم' => Carbon::SUNDAY,
            'دووشەم' => Carbon::MONDAY,
            'سێشەم' => Carbon::TUESDAY,
            'چوارشەم' => Carbon::WEDNESDAY,
            'پێنجشەم' => Carbon::THURSDAY,
            'هەینی' => Carbon::FRIDAY,
        ];

        foreach ($daysMap as $name => $dayInt) {
            if (mb_strpos($scheduleStr, $name) !== false) {
                if (!in_array($dayInt, $foundDays)) {
                    $foundDays[] = $dayInt;
                }
                $scheduleStr = str_replace([$name . 'مە', $name . 'ە', $name], '', $scheduleStr);
            }
        }

        if (mb_strpos($scheduleStr, 'شەم') !== false) {
            if (!in_array(Carbon::SATURDAY, $foundDays)) {
                $foundDays[] = Carbon::SATURDAY;
            }
        }

        $dates = collect();
        $start = Carbon::parse($lesson->start_date);
        $end = Carbon::parse($lesson->end_date);

        if (!empty($foundDays) && $start->lte($end)) {
            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                if (in_array($d->dayOfWeek, $foundDays)) {
                    $dates->push($d->format('Y-m-d'));
                }
            }
        }

        $dbDates = Attendance::where('lesson_id', $lesson->id)->pluck('date')->toArray();
        $dates = $dates->merge($dbDates)->unique()->sort()->values();

        $attendances = Attendance::where('lesson_id', $lesson->id)->get()->keyBy(function ($att) {
            return $att->student_id . '_' . $att->date;
        });

        return view('attendances.take', compact('lesson', 'students', 'dates', 'attendances'));
    }

    public function ajaxStore(Request $request, Lesson $lesson)
    {
        $user = Auth::user();
        if ($user->role === 'teacher' && $lesson->teacher_id !== ($user->teacher->id ?? 0)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'status' => 'required|in:ئامادە,نەهاتوو,مۆڵەت',
            'notes' => 'nullable|string|max:255', // وەرگرتنی هۆکاری مۆڵەت
        ]);

        $attendance = Attendance::updateOrCreate(
            [
                'lesson_id' => $lesson->id,
                'student_id' => $request->student_id,
                'date' => $request->date,
            ],
            [
                'status' => $request->status,
                'notes' => $request->notes, // سەیڤکردنی هۆکارەکە
            ]
        );

        return response()->json(['success' => true, 'attendance' => $attendance]);
    }
}
