<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                زانیاری مامۆستا: <span class="text-blue-600 dark:text-blue-400">{{ $teacher->full_name }}</span>
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('teachers.index') }}"
                    class="flex items-center justify-center gap-2 py-2 px-5 rounded-xl border bg-blue-600 hover:bg-blue-700 border-gray-200 dark:border-gray-700 shadow-md transition-all duration-200 transform hover:-translate-y-1 dark:bg-[#1f2937]">
                    <span class="text-base font-bold text-white">&larr; گەڕانەوە</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-[92%] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- زانیارییە کەسییەکان -->
            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl p-6 border border-gray-200 dark:border-gray-700 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                dir="rtl">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">ژمارەی مۆبایل</p>
                    <p class="font-black text-lg text-blue-600 dark:text-blue-400" dir="ltr">
                        {{ $teacher->phone_number ?: 'نییە' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">بەرواری لەدایکبوون</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">
                        {{ $teacher->date_of_birth ?: 'نەزانراو' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">ئەزموون</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $teacher->experience ?: 'نییە' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">باری خێزانداری</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">
                        {{ $teacher->marital_status ?: 'نەزانراو' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">بەرواری پەیوەندیکردن</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $teacher->join_date ?: 'نەزانراو' }}
                    </p>
                </div>
                <div class="lg:col-span-3">
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">بڕوانامەکان</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $teacher->certificates ?: 'نییە' }}
                    </p>
                </div>
                <div class="lg:col-span-2">
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">وانەکان (کاتی)</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $teacher->subjects ?: 'نییە' }}</p>
                </div>
                <div class="lg:col-span-2">
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">ناونیشان</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $teacher->address ?: 'نییە' }}</p>
                </div>
                <div class="col-span-full">
                    <p class="text-gray-500 dark:text-gray-400 mb-1 font-bold text-md">تێبینی</p>
                    <p class="font-bold text-lg text-gray-900 dark:text-white">{{ $teacher->notes ?: 'هیچ' }}</p>
                </div>
            </div>

            <!-- پێشینەی ئەو خولانەی وتوویەتیەوە -->
            <div class="mt-12 mb-6" dir="rtl">
                <h3 class="text-2xl font-black text-gray-900 dark:text-gray-100 flex items-center gap-2 mb-4">
                    🕒 پێشینەی وانە و خولەکان
                </h3>

                <!-- فلتەر بۆ وانەکانی مامۆستا -->
                <form method="GET" action="{{ route('teachers.show', $teacher->id) }}"
                    class="flex flex-wrap items-center gap-6 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 w-full">

                    <div class="flex items-center gap-3">
                        <label class="text-base font-bold text-gray-700 dark:text-gray-300 whitespace-nowrap">ناوی
                            وانە:</label>
                        <input type="text" name="lesson_name" value="{{ request('lesson_name') }}"
                            placeholder="بۆ نموونە: زمانی عەرەبی"
                            class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white text-base focus:ring-blue-500 py-2 px-4">
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit"
                            class="flex items-center justify-center gap-2 py-2 px-8 rounded-xl shadow-md bg-blue-600 hover:bg-blue-700 text-white font-bold">گەڕان</button>
                    </div>
                </form>
            </div>

            <!-- خشتەی خولەکان (Placeholder تا بەشی وانەکان دروست دەکەین) -->
            <div
                class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" dir="rtl">
                        <thead class="bg-gray-100 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-4 text-start text-md font-bold text-gray-700 dark:text-gray-300">ناوی
                                    وانە/خول</th>
                                <th class="px-6 py-4 text-start text-md font-bold text-gray-700 dark:text-gray-300">
                                    بەرواری دەستپێک</th>
                                <th class="px-6 py-4 text-start text-md font-bold text-gray-700 dark:text-gray-300">
                                    ژمارەی خوێندکار</th>
                                <th class="px-6 py-4 text-start text-md font-bold text-gray-700 dark:text-gray-300">
                                    تێبینی</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-md font-bold text-gray-900 dark:text-white">
                                    نموونە: زمانی عەرەبی</td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-md font-mono text-gray-600 dark:text-gray-400">
                                    2026-08-01</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-4 py-1.5 inline-flex items-center justify-center gap-1 text-sm font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded-lg">٢٥
                                        خوێندکار</span>
                                </td>
                                <td class="px-6 py-4 text-md text-gray-700 dark:text-gray-300">خولێکی سەرکەوتوو بوو</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
