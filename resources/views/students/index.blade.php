<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('بەشی خوێندکاران') }}
        </h2>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- پیامەکان -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- فۆڕمی زیادکردنی خوێندکار (ئینلاین و کۆمپاکت) -->
            <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">
                    تۆمارکردنی خوێندکاری نوێ</h3>

                <form method="POST" action="{{ route('students.store') }}" class="flex flex-wrap gap-4 items-end">
                    @csrf

                    <div class="flex-1 min-w-[200px]">
                        <x-input-label for="full_name" :value="__('ناوی تەواوی')" />
                        <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full"
                            required />
                    </div>

                    <div class="flex-1 min-w-[120px]">
                        <x-input-label for="gender" :value="__('ڕەگەز')" />
                        <select name="gender" id="gender"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                            <option value="نێر">نێر</option>
                            <option value="مێ">مێ</option>
                        </select>
                    </div>

                    <div class="flex-1 min-w-[150px]">
                        <x-input-label for="date_of_birth" :value="__('بەرواری لەدایکبوون')" />
                        <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full"
                            required />
                    </div>

                    <div class="flex-1 min-w-[150px]">
                        <x-input-label for="education_level" :value="__('ئاستی خوێندن')" />
                        <x-text-input id="education_level" name="education_level" type="text"
                            class="mt-1 block w-full" />
                    </div>

                    <div class="flex-1 min-w-[150px]">
                        <x-input-label for="phone_number" :value="__('ژمارەی مۆبایل')" />
                        <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" />
                    </div>

                    <div class="flex-1 min-w-[150px]">
                        <x-input-label for="marital_status" :value="__('باری خێزانداری')" />
                        <select name="marital_status" id="marital_status"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                            <option value="سەڵت">سەڵت</option>
                            <option value="خێزاندار">خێزاندار</option>
                        </select>
                    </div>

                    <div class="flex-1 min-w-[150px]">
                        <x-input-label for="join_date" :value="__('بەرواری پەیوەندیکردن')" />
                        <x-text-input id="join_date" name="join_date" type="date" class="mt-1 block w-full"
                            required />
                    </div>

                    <div class="flex-1 min-w-[120px]">
                        <x-input-label for="study_type" :value="__('جۆری خوێندن')" />
                        <select name="study_type" id="study_type"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                            <option value="ئاسایی">ئاسایی</option>
                            <option value="ڕەوزە">ڕەوزە</option>
                        </select>
                    </div>

                    <div class="flex-1 min-w-[100px]">
                        <x-input-label for="score" :value="__('نمرە')" />
                        <x-text-input id="score" name="score" type="number" step="0.01"
                            class="mt-1 block w-full" />
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <x-input-label for="address" :value="__('ناونیشان')" />
                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" />
                    </div>

                    <div class="flex-none pb-0.5">
                        <x-primary-button>{{ __('تۆمارکردن') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- بەشی فلتەرەکان و گەڕان (ئۆتۆماتیکی) -->
            <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">گەڕان و فلتەرکردن</h3>

                <!-- x-data لەگەڵ ڕووداوەکان بۆ ناردنی فۆڕمەکە بەشێوەی ئۆتۆماتیک -->
                <form method="GET" action="{{ route('students.index') }}" x-data="{ submitForm() { $el.submit(); } }"
                    class="space-y-4 mb-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">

                    <!-- گەڕانی گشتی -->
                    <div>
                        <x-text-input name="search" type="text" placeholder="گەڕان بەپێی ناو یان مۆبایل..."
                            value="{{ request('search') }}" class="w-full max-w-md"
                            x-on:input.debounce.700ms="submitForm" />
                    </div>

                    <!-- فلتەرە وردەکان -->
                    <div class="flex flex-wrap gap-6 items-center pt-2">

                        <!-- ڕەگەز -->
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">ڕەگەز:</span>
                            <div class="flex gap-3 text-sm text-gray-600 dark:text-gray-400">
                                <label class="flex items-center gap-1 cursor-pointer"><input type="radio"
                                        name="gender" value="هەردووکی" x-on:change="submitForm"
                                        {{ request('gender', 'هەردووکی') == 'هەردووکی' ? 'checked' : '' }}>
                                    هەردووکی</label>
                                <label class="flex items-center gap-1 cursor-pointer"><input type="radio"
                                        name="gender" value="نێر" x-on:change="submitForm"
                                        {{ request('gender') == 'نێر' ? 'checked' : '' }}> نێر</label>
                                <label class="flex items-center gap-1 cursor-pointer"><input type="radio"
                                        name="gender" value="مێ" x-on:change="submitForm"
                                        {{ request('gender') == 'مێ' ? 'checked' : '' }}> مێ</label>
                            </div>
                        </div>

                        <!-- جۆری خوێندن -->
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">جۆری خوێندن:</span>
                            <div class="flex gap-3 text-sm text-gray-600 dark:text-gray-400">
                                <label class="flex items-center gap-1 cursor-pointer"><input type="radio"
                                        name="study_type" value="هەردووکی" x-on:change="submitForm"
                                        {{ request('study_type', 'هەردووکی') == 'هەردووکی' ? 'checked' : '' }}>
                                    هەردووکی</label>
                                <label class="flex items-center gap-1 cursor-pointer"><input type="radio"
                                        name="study_type" value="ئاسایی" x-on:change="submitForm"
                                        {{ request('study_type') == 'ئاسایی' ? 'checked' : '' }}> ئاسایی</label>
                                <label class="flex items-center gap-1 cursor-pointer"><input type="radio"
                                        name="study_type" value="ڕەوزە" x-on:change="submitForm"
                                        {{ request('study_type') == 'ڕەوزە' ? 'checked' : '' }}> ڕەوزە</label>
                            </div>
                        </div>

                        <!-- باری خێزانداری -->
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">باری خێزانداری:</span>
                            <div class="flex gap-3 text-sm text-gray-600 dark:text-gray-400">
                                <label class="flex items-center gap-1 cursor-pointer"><input type="radio"
                                        name="marital_status" value="هەردووکی" x-on:change="submitForm"
                                        {{ request('marital_status', 'هەردووکی') == 'هەردووکی' ? 'checked' : '' }}>
                                    هەردووکی</label>
                                <label class="flex items-center gap-1 cursor-pointer"><input type="radio"
                                        name="marital_status" value="سەڵت" x-on:change="submitForm"
                                        {{ request('marital_status') == 'سەڵت' ? 'checked' : '' }}> سەڵت</label>
                                <label class="flex items-center gap-1 cursor-pointer"><input type="radio"
                                        name="marital_status" value="خێزاندار" x-on:change="submitForm"
                                        {{ request('marital_status') == 'خێزاندار' ? 'checked' : '' }}>
                                    خێزاندار</label>
                            </div>
                        </div>

                        <!-- بەرواری لەدایکبوون -->
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">لەدایکبوون:</span>
                            <x-text-input name="date_of_birth" type="date" value="{{ request('date_of_birth') }}"
                                x-on:change="submitForm" class="py-1 text-sm" />
                        </div>

                        <!-- بەرواری پەیوەندیکردن -->
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">پەیوەندیکردن:</span>
                            <x-text-input name="join_date" type="date" value="{{ request('join_date') }}"
                                x-on:change="submitForm" class="py-1 text-sm" />
                        </div>

                        <!-- ئاستی خوێندن -->
                        <div class="flex flex-col gap-1">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">ئاستی خوێندن:</span>
                            <x-text-input name="education_level" type="text" placeholder="ئاست..."
                                value="{{ request('education_level') }}" x-on:input.debounce.700ms="submitForm"
                                class="py-1 text-sm w-32" />
                        </div>

                    </div>
                </form>

                <!-- خشتەی خوێندکاران -->
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-gray-500 dark:text-gray-400 border dark:border-gray-700">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3 border-r dark:border-gray-600">ناوی تەواوی</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">ڕەگەز</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">مۆبایل</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">جۆری خوێندن</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">ئاستی خوێندن</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">نمرە</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td class="px-4 py-3 border-r dark:border-gray-700">{{ $student->full_name }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700">{{ $student->gender }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700">{{ $student->phone_number }}
                                    </td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700">{{ $student->study_type }}
                                    </td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700">
                                        {{ $student->education_level }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700">{{ $student->score }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-5 text-center">هیچ خوێندکارێک نەدۆزراوەتەوە.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- پاجینەیشن (پیشاندانی زانیارییەکان ١٠ بە ١٠) -->
                <div class="mt-4">
                    {{ $students->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
