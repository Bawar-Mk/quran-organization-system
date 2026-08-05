<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                پڕۆفایلی خوێندکار: <span class="text-blue-600 dark:text-blue-400">{{ $student->full_name }}</span>
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('students.index') }}"
                    class="py-2 px-5 rounded-xl border bg-gray-600 hover:bg-gray-700 text-white font-bold transition-all shadow-md">&larr;
                    گەڕانەوە</a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- زانیارییە کەسییەکان -->
            <div
                class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl p-6 border border-gray-200 dark:border-gray-700 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">ژمارەی مۆبایل</p>
                    <p class="font-black text-lg text-blue-600 dark:text-blue-400" dir="ltr">
                        {{ $student->phone_number ?: 'نییە' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">ڕەگەز</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $student->gender }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">بەرواری لەدایکبوون</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white font-mono">
                        {{ $student->date_of_birth }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">باری خێزانداری</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">
                        {{ $student->marital_status ?: 'نەزانراو' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">ئاستی خوێندن</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">
                        {{ $student->education_level ?: 'نەزانراو' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">جۆری خوێندن</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $student->study_type }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">بەرواری پەیوەندیکردن</p>
                    <p class="font-bold text-gray-900 dark:text-white font-mono text-sm">
                        {{ $student->join_date }}</p>
                </div>
                <div class="lg:col-span-4">
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">ناونیشان</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $student->address ?: 'نییە' }}</p>
                </div>
            </div>

            <!-- خشتەی خولە بەشداربووەکان -->
            <div
                class="mt-12 bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-xl font-black text-gray-800 dark:text-white flex items-center gap-2">
                        📚 پێشینەی وانە و خولەکان
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-900">
                            <tr>
                                <th
                                    class="px-6 py-4 text-start text-md font-bold text-gray-700 dark:text-gray-300 border-r dark:border-gray-600">
                                    ناوی وانە/خول</th>
                                <th
                                    class="px-6 py-4 text-start text-md font-bold text-gray-700 dark:text-gray-300 border-r dark:border-gray-600">
                                    مامۆستا</th>
                                <th
                                    class="px-6 py-4 text-start text-md font-bold text-gray-700 dark:text-gray-300 border-r dark:border-gray-600">
                                    دۆخی پارەدان</th>
                                <th
                                    class="px-6 py-4 text-start text-md font-bold text-gray-700 dark:text-gray-300 border-r dark:border-gray-600">
                                    نمرە / مەرج</th>
                                <th class="px-6 py-4 text-center text-md font-bold text-gray-700 dark:text-gray-300">
                                    بڕوانامە</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($lessons as $lesson)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-md font-bold text-gray-900 dark:text-white border-r dark:border-gray-700">
                                        <a href="{{ route('lessons.show', $lesson->id) }}"
                                            class="text-blue-600 hover:underline">{{ $lesson->name }}</a>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-md text-gray-700 dark:text-gray-300 border-r dark:border-gray-700">
                                        {{ $lesson->teacher->full_name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center border-r dark:border-gray-700">
                                        @if ($lesson->pivot->is_paid)
                                            <span
                                                class="px-4 py-1 inline-flex text-sm font-bold rounded-full bg-green-100 text-green-800">داویەتی
                                                ✅</span>
                                        @else
                                            <span
                                                class="px-4 py-1 inline-flex text-sm font-bold rounded-full bg-red-100 text-red-800">نەیداوە
                                                ❌</span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-center border-r dark:border-gray-700 font-mono">
                                        <span
                                            class="font-black text-lg {{ $lesson->pivot->score >= $lesson->passing_score ? 'text-green-600' : 'text-red-500' }}">{{ $lesson->pivot->score ?? 0 }}</span>
                                        <span class="text-gray-400"> / {{ $lesson->passing_score }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($lesson->pivot->score >= $lesson->passing_score)
                                            <a href="{{ route('lessons.certificate', [$lesson->id, $student->id]) }}"
                                                target="_blank"
                                                class="px-3 py-1 bg-yellow-400 text-yellow-900 font-bold text-xs rounded hover:bg-yellow-500 hover:shadow-md transition-all">🖨️
                                                پرینتی بڕوانامە</a>
                                        @else
                                            <span class="text-gray-400 text-xs">بێ بڕوانامە</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 font-bold">ئەم
                                        خوێندکارە بەشداری هیچ خولێکی نەکردووە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
