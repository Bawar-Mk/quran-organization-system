<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight" dir="rtl">
            {{ __('📊 داشبۆردی سەرەکی') }}
        </h2>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- کارتەکانی ئامار -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- کارتی خوێندکاران -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-r-4 border-blue-500 flex items-center justify-between transform transition hover:-translate-y-1 hover:shadow-xl">
                    <div>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">کۆی خوێندکاران</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white font-mono">
                            {{ $stats['students_count'] }}</p>
                    </div>
                    <div class="text-5xl opacity-80">👨‍🎓</div>
                </div>

                <!-- کارتی مامۆستایان -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-r-4 border-purple-500 flex items-center justify-between transform transition hover:-translate-y-1 hover:shadow-xl">
                    <div>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">کۆی مامۆستایان</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white font-mono">
                            {{ $stats['teachers_count'] }}</p>
                    </div>
                    <div class="text-5xl opacity-80">👨‍🏫</div>
                </div>

                <!-- کارتی وانە کاراکان -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-r-4 border-green-500 flex items-center justify-between transform transition hover:-translate-y-1 hover:shadow-xl">
                    <div>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">وانە و خولە کاراکان</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white font-mono">
                            {{ $stats['active_lessons_count'] }}</p>
                    </div>
                    <div class="text-5xl opacity-80">📚</div>
                </div>

                <!-- کارتی پارەدان -->
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border-r-4 border-yellow-500 flex items-center justify-between transform transition hover:-translate-y-1 hover:shadow-xl">
                    <div>
                        <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-1">کۆی پارەدانەکان</p>
                        <p class="text-3xl font-black text-gray-900 dark:text-white font-mono">
                            {{ $stats['total_paid_enrollments'] }}</p>
                    </div>
                    <div class="text-5xl opacity-80">💵</div>
                </div>
            </div>

            <!-- خشتەی دوایین وانەکان -->
            <div
                class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div
                    class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-black text-gray-800 dark:text-white flex items-center gap-2">
                        🕒 دوایین وانە چالاکەکان
                    </h3>
                    <a href="{{ route('lessons.index') }}"
                        class="text-sm font-bold text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        بینینی هەمووی &larr;
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-right text-gray-500 dark:text-gray-400">
                        <thead
                            class="text-xs text-gray-700 uppercase bg-white dark:bg-gray-900 border-b dark:border-gray-700">
                            <tr>
                                <th class="px-6 py-4 font-bold">ناوی وانە</th>
                                <th class="px-6 py-4 font-bold">مامۆستا</th>
                                <th class="px-6 py-4 font-bold">بەرواری دەستپێک</th>
                                <th class="px-6 py-4 font-bold">کاتی وانە</th>
                                <th class="px-6 py-4 font-bold text-center">کردار</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($latestLessons as $lesson)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                    <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">{{ $lesson->name }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">
                                        {{ $lesson->teacher->full_name }}</td>
                                    <td class="px-6 py-4 font-mono text-gray-600 dark:text-gray-400">
                                        {{ $lesson->start_date }}</td>
                                    <td class="px-6 py-4 text-gray-700 dark:text-gray-300">{{ $lesson->schedule }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('lessons.show', $lesson->id) }}"
                                            class="inline-flex items-center justify-center px-4 py-2 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 rounded-lg font-bold text-xs hover:bg-blue-200 transition-colors">
                                            زانیاری
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 font-bold">هیچ
                                        وانەیەکی چالاک نییە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
