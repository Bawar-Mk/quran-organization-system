<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'تەنها ئەدمین دەتوانێت ئەم پەڕەیە ببینێت');
        }

        $query = User::with('teacher');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('username', 'like', "%{$search}%");
        }

        $users = $query->orderBy('created_at', 'desc')->get();
        $teachers = Teacher::all();

        return view('users.index', compact('users', 'teachers'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|string|min:4',
            'role' => 'required|in:admin,user,teacher',
            'teacher_id' => 'required_if:role,teacher|nullable|exists:teachers,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'password' => $validated['password'],
            'role' => $validated['role'],
        ]);

        if ($user->role === 'teacher' && !empty($validated['teacher_id'])) {
            $teacher = Teacher::find($validated['teacher_id']);
            $teacher->user_id = $user->id;
            $teacher->save();
        }

        return redirect()->route('users.index')->with('success', 'بەکارهێنەری نوێ بە سەرکەوتوویی زیادکرا');
    }

    public function update(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'role' => 'required|in:admin,user,teacher',
            'teacher_id' => 'required_if:role,teacher|nullable|exists:teachers,id',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:4';
        }

        $validated = $request->validate($rules);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'role' => $validated['role'],
            'password' => $validated['password'] ?? $user->password,
        ]);

        if ($user->role === 'teacher' && !empty($validated['teacher_id'])) {
            Teacher::where('user_id', $user->id)->update(['user_id' => null]);

            $teacher = Teacher::find($validated['teacher_id']);
            $teacher->user_id = $user->id;
            $teacher->save();
        } else {
            Teacher::where('user_id', $user->id)->update(['user_id' => null]);
        }

        return redirect()->route('users.index')->with('success', 'زانیاری بەکارهێنەرەکە نوێکرایەوە');
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'ناتوانیت هەژماری خۆت بسڕیتەوە!');
        }

        Teacher::where('user_id', $user->id)->update(['user_id' => null]);

        $user->delete();

        return redirect()->route('users.index')->with('success', 'بەکارهێنەرەکە بە سەرکەوتوویی سڕایەوە');
    }

    public function toggleStatus(User $user)
    {
        if (Auth::user()->role !== 'admin') abort(403);

        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'ناتوانیت هەژماری خۆت ناچالاک بکەیت!');
        }

        $user->update([
            'is_active' => !$user->is_active
        ]);

        $status = $user->is_active ? 'چالاک کرا' : 'ناچالاک کرا';
        return redirect()->route('users.index')->with('success', 'بەکارهێنەرەکە بە سەرکەوتوویی ' . $status);
    }
}
