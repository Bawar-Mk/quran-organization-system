<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('بەشی مامۆستایان') }}
        </h2>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif

            <!-- فۆڕمی زیادکردنی مامۆستا -->
            <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">
                    تۆمارکردنی مامۆستای نوێ</h3>

                <form method="POST" action="{{ route('teachers.store') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    @csrf

                    <div>
                        <x-input-label for="full_name" :value="__('ناوی تەواوی')" />
                        <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full"
                            required />
                    </div>

                    <div>
                        <x-input-label for="phone_number" :value="__('ژمارەی مۆبایل')" />
                        <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-input-label for="date_of_birth" :value="__('بەرواری لەدایکبوون')" />
                        <x-text-input id="date_of_birth" name="date_of_birth" type="date"
                            class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-input-label for="marital_status" :value="__('باری خێزانداری')" />
                        <select name="marital_status" id="marital_status"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm">
                            <option value="سەڵت">سەڵت</option>
                            <option value="خێزاندار">خێزاندار</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="join_date" :value="__('بەرواری پەیوەندیکردن')" />
                        <x-text-input id="join_date" name="join_date" type="date" class="mt-1 block w-full" />
                    </div>

                    <div>
                        <x-input-label for="experience" :value="__('ئەزموونی وانەوتنەوە')" placeholder="بۆ نموونە: ٥ ساڵ" />
                        <x-text-input id="experience" name="experience" type="text" class="mt-1 block w-full" />
                    </div>

                    <div class="lg:col-span-2">
                        <x-input-label for="subjects" :value="__('وانەکان (بە کۆما جیای بکەرەوە)')" />
                        <x-text-input id="subjects" name="subjects" type="text" class="mt-1 block w-full"
                            placeholder="قورئان، زمانی عەرەبی..." />
                    </div>

                    <div class="lg:col-span-2">
                        <x-input-label for="certificates" :value="__('بڕوانامەکان')" />
                        <x-text-input id="certificates" name="certificates" type="text" class="mt-1 block w-full"
                            placeholder="بەکالۆریۆس، مۆڵەتی قورئان..." />
                    </div>

                    <div class="lg:col-span-2">
                        <x-input-label for="address" :value="__('ناونیشان')" />
                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" />
                    </div>

                    <div class="lg:col-span-3">
                        <x-input-label for="notes" :value="__('تێبینی')" />
                        <x-text-input id="notes" name="notes" type="text" class="mt-1 block w-full" />
                    </div>

                    <div class="lg:col-span-1 flex justify-end pb-0.5">
                        <x-primary-button class="w-full justify-center">{{ __('تۆمارکردن') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- بەشی فلتەرەکان و گەڕان -->
            <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">گەڕان و فلتەرکردن</h3>

                <form method="GET" action="{{ route('teachers.index') }}" x-data="{ submitForm() { $el.submit(); } }"
                    class="space-y-4 mb-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                    <div>
                        <x-text-input name="search" type="text" placeholder="گەڕان بەپێی ناو، مۆبایل یان وانە..."
                            value="{{ request('search') }}" class="w-full max-w-md"
                            x-on:input.debounce.700ms="submitForm" />
                    </div>
                    <div class="flex flex-wrap gap-6 items-center pt-2">
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
                                        {{ request('marital_status') == 'خێزاندار' ? 'checked' : '' }}> خێزاندار</label>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="w-full text-right text-gray-500 dark:text-gray-400 border dark:border-gray-700">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3 border-r dark:border-gray-600">ناوی تەواوی</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">مۆبایل</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">وانەکان</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">ئەزموون</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600 text-center">کردارەکان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($teachers as $teacher)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td class="px-4 py-3 border-r dark:border-gray-700">{{ $teacher->full_name }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700" dir="ltr">
                                        {{ $teacher->phone_number }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700">
                                        {{ $teacher->subjects ?: '-' }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700">
                                        {{ $teacher->experience ?: '-' }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('teachers.show', $teacher->id) }}"
                                                class="text-blue-600 dark:text-blue-400 hover:underline">زانیاری</a>
                                            <a href="#"
                                                class="text-green-600 dark:text-green-400 hover:underline">گۆڕانکاری</a>
                                            <form action="{{ route('teachers.destroy', $teacher->id) }}"
                                                method="POST" class="inline"
                                                onsubmit="return confirm('دڵنیایت لە سڕینەوەی ئەم مامۆستایە؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 dark:text-red-400 hover:underline">سڕینەوە</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-5 text-center">هیچ مامۆستایەک نەدۆزراوەتەوە.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $teachers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
