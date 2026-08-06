<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index(Request $request)
    {
        // ------------------ بەشی یەکەم: پارەدانی خوێندکاران (وەک خۆی ماوەتەوە) ------------------
        $lessons = Lesson::all();

        $queryStudents = DB::table('lesson_student')
            ->join('students', 'lesson_student.student_id', '=', 'students.id')
            ->join('lessons', 'lesson_student.lesson_id', '=', 'lessons.id')
            ->select(
                'students.full_name as student_name',
                'lessons.name as lesson_name',
                'lesson_student.is_paid',
                'lesson_student.created_at as enroll_date'
            );

        if ($request->filled('lesson_id')) {
            $queryStudents->where('lessons.id', $request->lesson_id);
        }

        if ($request->filled('payment_status')) {
            if ($request->payment_status == 'paid') {
                $queryStudents->where('lesson_student.is_paid', 1);
            } elseif ($request->payment_status == 'unpaid') {
                $queryStudents->where('lesson_student.is_paid', 0);
            }
        }

        $studentPayments = $queryStudents->latest('lesson_student.created_at')->paginate(10, ['*'], 'students_page')->appends($request->all());

        $totalEnrolled = DB::table('lesson_student')->count();
        $totalPaid = DB::table('lesson_student')->where('is_paid', 1)->count();
        $totalUnpaid = DB::table('lesson_student')->where('is_paid', 0)->count();


        // ------------------ بەشی دووەم: خەرجی و داهاتە گشتییەکان (ئەمە نوێیە) ------------------
        $queryTransactions = Transaction::with('user');

        if ($request->filled('type')) {
            $queryTransactions->where('type', $request->type);
        }

        $transactions = $queryTransactions->latest('transaction_date')->paginate(10, ['*'], 'trans_page')->appends($request->all());

        // ئامارە داراییەکان
        $totalIncome = Transaction::where('type', 'income')->sum('amount');
        $totalExpense = Transaction::where('type', 'expense')->sum('amount');
        $netProfit = $totalIncome - $totalExpense;

        return view('finance.index', compact(
            'studentPayments',
            'lessons',
            'totalEnrolled',
            'totalPaid',
            'totalUnpaid',
            'transactions',
            'totalIncome',
            'totalExpense',
            'netProfit'
        ));
    }

    public function storeTransaction(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'transaction_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        Transaction::create([
            'title' => $request->title,
            'type' => $request->type,
            'amount' => $request->amount,
            'transaction_date' => $request->transaction_date,
            'notes' => $request->notes,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('finance.index')->with('success', 'مامەڵە داراییەکە بە سەرکەوتوویی تۆمارکرا.');
    }

    public function destroyTransaction(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('finance.index')->with('success', 'مامەڵە داراییەکە سڕایەوە.');
    }
}
