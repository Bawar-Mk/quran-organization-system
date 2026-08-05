<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                زانیاری وانە: <span class="text-blue-600">{{ $lesson->name }}</span>
            </h2>
            <a href="{{ route('lessons.index') }}"
                class="py-2 px-5 rounded-xl border bg-gray-600 hover:bg-gray-700 text-white font-bold">&larr;
                گەڕانەوە</a>
        </div>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8 space-y-8">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}</div>
            @endif

            <!-- زانیاری سەرەوەی وانەکە -->
            <div
                class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl p-6 border {{ $lesson->status == 'active' ? 'border-blue-300' : 'border-red-300' }} grid grid-cols-1 md:grid-cols-5 gap-6">
                <div>
                    <p class="text-gray-500 font-bold mb-1">مامۆستا</p>
                    <p class="font-black text-lg text-gray-900 dark:text-white">{{ $lesson->teacher->full_name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-bold mb-1">بەروار</p>
                    <p class="font-bold text-gray-900 dark:text-white font-mono text-sm">{{ $lesson->start_date }} <br>
                        {{ $lesson->end_date }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-bold mb-1">کاتی وانە</p>
                    <p class="font-bold text-gray-900 dark:text-white">{{ $lesson->schedule }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-bold mb-1">مەرجی بڕوانامە</p>
                    <p class="font-black text-xl text-green-600">{{ $lesson->passing_score }}</p>
                </div>
                <div>
                    <p class="text-gray-500 font-bold mb-1">دۆخ</p>
                    <span
                        class="px-3 py-1 text-sm font-bold rounded-full {{ $lesson->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $lesson->status == 'active' ? 'بەردەوامە' : 'کۆتایی هاتووە' }}
                    </span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- بەشی پلەبەندی (چەپ) -->
                <div class="lg:col-span-1 bg-white dark:bg-gray-800 shadow sm:rounded-lg p-5">
                    <h3 class="text-lg font-black text-gray-900 dark:text-white mb-4 border-b pb-2">🏆 پلەبەندی
                        خوێندکاران</h3>
                    <ul class="space-y-3">
                        @foreach ($students as $index => $student)
                            <li class="flex justify-between items-center bg-gray-50 dark:bg-gray-700 p-2 rounded">
                                <span class="font-bold flex items-center gap-2">
                                    <span
                                        class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold {{ $index == 0 ? 'bg-yellow-400 text-white' : ($index == 1 ? 'bg-gray-300' : 'bg-orange-300 text-white') }}">{{ $index + 1 }}</span>
                                    {{ $student->full_name }}
                                </span>
                                <span
                                    class="font-black font-mono {{ $student->pivot->score >= $lesson->passing_score ? 'text-green-600' : 'text-red-500' }}">{{ $student->pivot->score ?? 0 }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- پێشینە و لیستی خوێندکاران (ڕاست) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- تەنها ئەگەر ئەکتیڤ بوو دەتوانیت خوێندکار زیاد بکەیت -->
                    @if ($lesson->status == 'active')
                        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-5">
                            <h3 class="text-md font-bold text-gray-900 dark:text-white mb-3">➕ بەشداریکردنی خوێندکاری
                                نوێ لە خولەکە</h3>
                            <form action="{{ route('lessons.enroll', $lesson->id) }}" method="POST"
                                class="flex flex-wrap gap-3 items-end">
                                @csrf
                                <div class="flex-1">
                                    <select name="student_id" class="w-full border-gray-300 rounded-md" required>
                                        <option value="">خوێندکار هەڵبژێرە...</option>
                                        @foreach ($allStudents as $stu)
                                            <option value="{{ $stu->id }}">{{ $stu->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <select name="is_paid" class="w-full border-gray-300 rounded-md">
                                        <option value="1">پارەی داوە 💵</option>
                                        <option value="0" selected>پارەی نەداوە ❌</option>
                                    </select>
                                </div>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white font-bold rounded-md hover:bg-blue-700">زیادکردن</button>
                            </form>
                        </div>
                    @endif

                    <!-- خشتەی خوێندکارانی بەشداربوو -->
                    <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-5 overflow-x-auto">
                        <h3 class="text-lg font-black text-gray-900 dark:text-white mb-4">👥 لیستی خوێندکارانی خول</h3>
                        <table class="w-full text-right text-gray-500 dark:text-gray-400 border">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th class="px-4 py-3 border-r">ناوی تەواوی</th>
                                    <th class="px-4 py-3 border-r">دۆخی پارەدان</th>
                                    <th class="px-4 py-3 border-r">کۆنمرە</th>
                                    <th class="px-4 py-3 border-r text-center">کردارەکان</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($students as $student)
                                    <tr class="border-b">
                                        <td class="px-4 py-3 font-bold text-gray-900 border-r">
                                            {{ $student->full_name }}</td>
                                        <td class="px-4 py-3 border-r">
                                            @if ($student->pivot->is_paid)
                                                <span class="text-green-600 font-bold">داویەتی ✅</span>
                                            @else
                                                <span class="text-red-600 font-bold">نەیداوە ❌</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 border-r">
                                            @if ($lesson->status == 'active')
                                                <!-- گۆڕینی نمرە بۆ وانەی ئەکتیڤ -->
                                                <form
                                                    action="{{ route('lessons.updateScore', [$lesson->id, $student->id]) }}"
                                                    method="POST" class="flex items-center gap-2">
                                                    @csrf @method('PATCH')
                                                    <input type="number" name="score"
                                                        value="{{ $student->pivot->score }}"
                                                        class="w-20 p-1 border-gray-300 rounded text-center font-mono">
                                                    <button type="submit"
                                                        class="text-xs bg-indigo-100 text-indigo-700 font-bold px-2 py-1 rounded hover:bg-indigo-200">سەیڤ</button>
                                                </form>
                                            @else
                                                <span
                                                    class="font-black text-lg font-mono">{{ $student->pivot->score ?? 0 }}</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 border-r text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                <!-- دوگمەی بڕوانامە تەنها ئەگەر نمرەی هێنابێت -->
                                                @if ($student->pivot->score >= $lesson->passing_score)
                                                    <a href="{{ route('lessons.certificate', [$lesson->id, $student->id]) }}"
                                                        target="_blank"
                                                        class="px-3 py-1 bg-yellow-400 text-yellow-900 font-bold text-xs rounded hover:bg-yellow-500 hover:shadow-md transition-all">🖨️
                                                        پرینتی بڕوانامە</a>
                                                @else
                                                    <span class="text-gray-400 text-xs">بێ بڕوانامە</span>
                                                @endif

                                                <!-- دوگمەی سڕینەوە تەنها لە وانەی ئەکتیڤ دەردەکەوێت -->
                                                @if ($lesson->status == 'active')
                                                    <form
                                                        action="{{ route('lessons.removeStudent', [$lesson->id, $student->id]) }}"
                                                        method="POST"
                                                        onsubmit="confirmAction(event, this, 'دڵنیایت لە لابردنی ئەم خوێندکارە؟', 'خوێندکارەکە لە خولەکە دەکرێتە دەرەوە و نمرەکەشی دەسڕێتەوە.');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 font-bold text-sm hover:underline">سڕینەوە</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
