<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">بەشی وانەکان</h2>
    </x-slot>

    @if ($errors->any())
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'هەڵەیەک هەیە!',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    icon: 'error',
                    confirmButtonText: 'تێگەیشتم',
                    confirmButtonColor: '#d33'
                });
            });
        </script>
    @endif

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- فۆڕمی زیادکردنی وانە -->
            <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">
                    تۆمارکردنی وانەی نوێ</h3>
                <form method="POST" action="{{ route('lessons.store') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-start">
                    @csrf
                    <div>
                        <x-input-label for="name" :value="__('ناوی وانە')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 w-full"
                            value="{{ old('name') }}" required />
                    </div>
                    <div>
                        <x-input-label for="teacher_id" :value="__('مامۆستای وانە')" />
                        <select name="teacher_id" id="teacher_id"
                            class="mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                            required>
                            <option value="">هەڵبژێرە...</option>
                            @foreach ($teachers as $teacher)
                                <option value="{{ $teacher->id }}"
                                    {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>{{ $teacher->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <x-input-label for="start_date" :value="__('بەرواری دەستپێک')" />
                        <x-text-input id="start_date" name="start_date" type="date" class="mt-1 w-full"
                            value="{{ old('start_date') }}" required />
                    </div>
                    <div>
                        <x-input-label for="end_date" :value="__('بەرواری کۆتایی')" />
                        <x-text-input id="end_date" name="end_date" type="date" class="mt-1 w-full"
                            value="{{ old('end_date') }}" required />
                    </div>

                    <!-- بەشی هەڵبژاردنی ڕۆژەکان و کات بە شێوازی چوارگۆشە -->
                    <div
                        class="lg:col-span-4 bg-blue-50 dark:bg-gray-900/50 p-5 rounded-xl border border-blue-100 dark:border-gray-700 mt-2">
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
                            <div class="lg:col-span-3">
                                <x-input-label :value="__('ڕۆژەکانی وانە هەڵبژێرە:')"
                                    class="mb-3 text-base text-blue-700 dark:text-blue-400 font-black" />
                                <div class="flex flex-wrap gap-3">
                                    @foreach (['شەممە', 'یەکشەممە', 'دووشەممە', 'سێشەممە', 'چوارشەممە', 'پێنجشەممە', 'هەینی'] as $day)
                                        <label
                                            class="flex items-center gap-2 cursor-pointer bg-white dark:bg-gray-800 px-4 py-2 rounded-lg shadow-sm border border-gray-200 dark:border-gray-600 hover:bg-blue-100 dark:hover:bg-gray-700 transition-colors">
                                            <input type="checkbox" name="days[]" value="{{ $day }}"
                                                class="rounded w-5 h-5 border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <span
                                                class="text-sm font-bold dark:text-gray-300">{{ $day }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <div>
                                <x-input-label for="time" :value="__('کاتی وانە (کاتژمێر):')"
                                    class="mb-3 text-base text-blue-700 dark:text-blue-400 font-black" />
                                <input type="time" name="time" id="time"
                                    class="mt-1 w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 font-mono shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                    required>
                            </div>
                        </div>
                    </div>

                    <div>
                        <x-input-label for="passing_score" :value="__('مەرجی دەرچوون (نمرە)')" />
                        <x-text-input id="passing_score" name="passing_score" type="number" class="mt-1 w-full"
                            value="{{ old('passing_score') }}" required />
                    </div>
                    <div class="flex items-end lg:col-span-3 justify-end pb-0.5">
                        <x-primary-button
                            class="w-full md:w-auto px-10 py-3">{{ __('تۆمارکردنی وانە') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- خشتەی وانە بەردەستەکان -->
            <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-black text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">🟢 وانە
                    کارا و بەردەستەکان</h3>
                <div class="overflow-x-auto relative">
                    <table class="w-full text-right text-gray-500 dark:text-gray-400 border dark:border-gray-700">
                        <thead class="text-xs text-gray-700 uppercase bg-blue-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3 border-r dark:border-gray-600">ناوی وانە</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">مامۆستا</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">ڕۆژەکان و کات</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600 text-center">کردارەکان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($activeLessons as $lesson)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td
                                        class="px-4 py-3 border-r dark:border-gray-700 font-bold text-gray-900 dark:text-white">
                                        {{ $lesson->name }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700">
                                        {{ $lesson->teacher->full_name }}</td>
                                    <td
                                        class="px-4 py-3 border-r dark:border-gray-700 text-sm font-bold text-gray-700 dark:text-gray-300">
                                        {{ $lesson->schedule }}
                                    </td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700 text-center">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('lessons.show', $lesson->id) }}"
                                                class="text-blue-600 font-bold hover:underline">زانیاری</a>

                                            <!-- مۆدڵی گۆڕانکاری -->
                                            <div x-data="{ openEditModal: false }" class="inline">
                                                <button @click="openEditModal = true" type="button"
                                                    class="text-green-600 font-bold hover:underline">گۆڕانکاری</button>

                                                <template x-teleport="body">
                                                    <div x-show="openEditModal" style="display: none;"
                                                        class="fixed inset-0 z-50 overflow-y-auto"
                                                        aria-labelledby="modal-title" role="dialog" aria-modal="true"
                                                        dir="rtl">
                                                        <div
                                                            class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                                                            <div x-show="openEditModal" @click="openEditModal = false"
                                                                class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
                                                                aria-hidden="true"></div>
                                                            <span
                                                                class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                                aria-hidden="true">&#8203;</span>

                                                            <div x-show="openEditModal"
                                                                class="inline-block relative align-bottom bg-white dark:bg-gray-800 rounded-2xl text-right overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full border border-gray-200 dark:border-gray-700">
                                                                <form method="POST"
                                                                    action="{{ route('lessons.update', $lesson->id) }}">
                                                                    @csrf @method('PATCH')
                                                                    <div
                                                                        class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                        <div
                                                                            class="flex justify-between items-center mb-5 border-b dark:border-gray-700 pb-3">
                                                                            <h3
                                                                                class="text-xl font-black text-gray-900 dark:text-white">
                                                                                ✏️ گۆڕانکاری لە وانەی: <span
                                                                                    class="text-blue-600">{{ $lesson->name }}</span>
                                                                            </h3>
                                                                            <button type="button"
                                                                                @click="openEditModal = false"
                                                                                class="text-gray-400 hover:text-red-500"><svg
                                                                                    class="h-7 w-7" fill="none"
                                                                                    viewBox="0 0 24 24"
                                                                                    stroke="currentColor">
                                                                                    <path stroke-linecap="round"
                                                                                        stroke-linejoin="round"
                                                                                        stroke-width="2"
                                                                                        d="M6 18L18 6M6 6l12 12" />
                                                                                </svg></button>
                                                                        </div>
                                                                        <div
                                                                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 text-right items-start">
                                                                            <div class="lg:col-span-2"><x-input-label
                                                                                    :value="__('ناوی وانە')" /><x-text-input
                                                                                    name="name" type="text"
                                                                                    class="mt-1 block w-full"
                                                                                    value="{{ $lesson->name }}"
                                                                                    required /></div>
                                                                            <div class="lg:col-span-2">
                                                                                <x-input-label :value="__('مامۆستای وانە')" />
                                                                                <select name="teacher_id"
                                                                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                                                                    required>
                                                                                    @foreach ($teachers as $t)
                                                                                        <option
                                                                                            value="{{ $t->id }}"
                                                                                            {{ $lesson->teacher_id == $t->id ? 'selected' : '' }}>
                                                                                            {{ $t->full_name }}
                                                                                        </option>
                                                                                    @endforeach
                                                                                </select>
                                                                            </div>
                                                                            <div><x-input-label
                                                                                    :value="__('بەرواری دەستپێک')" /><x-text-input
                                                                                    name="start_date" type="date"
                                                                                    class="mt-1 block w-full"
                                                                                    value="{{ $lesson->start_date }}"
                                                                                    required /></div>
                                                                            <div><x-input-label
                                                                                    :value="__('بەرواری کۆتایی')" /><x-text-input
                                                                                    name="end_date" type="date"
                                                                                    class="mt-1 block w-full"
                                                                                    value="{{ $lesson->end_date }}"
                                                                                    required /></div>

                                                                            <!-- بەشی ڕۆژەکانی وانە بۆ ناو مۆدڵ -->
                                                                            <div
                                                                                class="lg:col-span-4 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg border border-gray-200 dark:border-gray-600 mt-2">
                                                                                <div
                                                                                    class="grid grid-cols-1 lg:grid-cols-4 gap-4">
                                                                                    <div class="lg:col-span-3">
                                                                                        <x-input-label
                                                                                            :value="__(
                                                                                                'ڕۆژەکان هەڵبژێرە',
                                                                                            )"
                                                                                            class="mb-2" />
                                                                                        <div
                                                                                            class="flex flex-wrap gap-2">
                                                                                            @foreach (['شەممە', 'یەکشەممە', 'دووشەممە', 'سێشەممە', 'چوارشەممە', 'پێنجشەممە', 'هەینی'] as $day)
                                                                                                <label
                                                                                                    class="flex items-center gap-1.5 bg-white dark:bg-gray-800 px-3 py-1.5 rounded shadow-sm border border-gray-200 dark:border-gray-600 cursor-pointer">
                                                                                                    <input
                                                                                                        type="checkbox"
                                                                                                        name="days[]"
                                                                                                        value="{{ $day }}"
                                                                                                        class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                                                                        {{ str_contains($lesson->schedule, $day) ? 'checked' : '' }}>
                                                                                                    <span
                                                                                                        class="text-sm font-bold dark:text-gray-300">{{ $day }}</span>
                                                                                                </label>
                                                                                            @endforeach
                                                                                        </div>
                                                                                    </div>
                                                                                    <div>
                                                                                        @php
                                                                                            preg_match(
                                                                                                '/([0-9]{1,2}:[0-9]{2} [A-Z]{2})/',
                                                                                                $lesson->schedule,
                                                                                                $matches,
                                                                                            );
                                                                                            $timeVal = isset(
                                                                                                $matches[1],
                                                                                            )
                                                                                                ? date(
                                                                                                    'H:i',
                                                                                                    strtotime(
                                                                                                        $matches[1],
                                                                                                    ),
                                                                                                )
                                                                                                : '';
                                                                                        @endphp
                                                                                        <x-input-label
                                                                                            :value="__('کاتی وانە')"
                                                                                            class="mb-2" />
                                                                                        <input type="time"
                                                                                            name="time"
                                                                                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 font-mono shadow-sm"
                                                                                            value="{{ $timeVal }}"
                                                                                            required>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div><x-input-label
                                                                                    :value="__('مەرجی بڕوانامە')" /><x-text-input
                                                                                    name="passing_score"
                                                                                    type="number"
                                                                                    class="mt-1 block w-full"
                                                                                    value="{{ $lesson->passing_score }}"
                                                                                    required /></div>
                                                                        </div>
                                                                    </div>
                                                                    <div
                                                                        class="bg-gray-50 dark:bg-gray-700/30 px-4 py-4 sm:px-6 flex gap-3 justify-end border-t dark:border-gray-700 rounded-b-2xl">
                                                                        <button type="button"
                                                                            @click="openEditModal = false"
                                                                            class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg font-bold hover:bg-gray-50 transition-colors">پاشگەزبوونەوە</button>
                                                                        <button type="submit"
                                                                            class="px-5 py-2.5 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 shadow-md transition-colors">سەیڤکردنی
                                                                            گۆڕانکاری</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>

                                            <form action="{{ route('lessons.destroy', $lesson->id) }}" method="POST"
                                                class="inline"
                                                onsubmit="confirmAction(event, this, 'دڵنیایت لە سڕینەوەی وانەکە؟', 'بە سڕینەوەی ئەم وانەیە، تەواوی بەشداربووان دەسڕێتەوە!');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 font-bold hover:underline">سڕینەوە</button>
                                            </form>
                                            @if (\Carbon\Carbon::now()->format('Y-m-d') >= $lesson->end_date)
                                                <form action="{{ route('lessons.finish', $lesson->id) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="confirmAction(event, this, 'دڵنیایت لە کۆتایی هێنان؟', 'ئەم وانەیە دەچێتە لیستی کۆتایی هاتووەکانەوە.', 'question', 'بەڵێ، کۆتایی پێ بهێنە', '#10B981');">
                                                    @csrf @method('PATCH')
                                                    <button type="submit"
                                                        class="px-3 py-1 bg-red-600 text-white rounded-md font-bold text-xs hover:bg-red-700">کۆتایی
                                                        هێنان</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-5 text-center font-bold text-gray-500">هیچ
                                        وانەیەکی بەردەست نییە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- خشتەی دووەم: وانە کۆتایی هاتووەکان وەک خۆیەتی -->

        </div>
    </div>
</x-app-layout>
