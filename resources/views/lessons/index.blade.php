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
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
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
                    <div class="lg:col-span-2">
                        <x-input-label for="schedule" :value="__('کاتی وانە (نموونە: شەممە و سێشەممە ٤:٠٠ تا ٦:٠٠)')" />
                        <x-text-input id="schedule" name="schedule" type="text" class="mt-1 w-full"
                            value="{{ old('schedule') }}" required />
                    </div>
                    <div>
                        <x-input-label for="passing_score" :value="__('نمرەی بڕوانامە (نموونە: ٨٠)')" />
                        <x-text-input id="passing_score" name="passing_score" type="number" class="mt-1 w-full"
                            value="{{ old('passing_score') }}" required />
                    </div>
                    <div class="flex justify-end pb-0.5">
                        <x-primary-button class="w-full justify-center">تۆمارکردن</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- خشتەی یەکەم: وانە بەردەستەکان -->
            <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-black text-blue-600 dark:text-blue-400 mb-4 flex items-center gap-2">🟢 وانە
                    کارا و بەردەستەکان</h3>
                <div class="overflow-x-auto relative">
                    <table class="w-full text-right text-gray-500 dark:text-gray-400 border dark:border-gray-700">
                        <thead class="text-xs text-gray-700 uppercase bg-blue-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3 border-r dark:border-gray-600">ناوی وانە</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">مامۆستا</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">دەستپێک و کۆتایی</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">مەرجی بڕوانامە</th>
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
                                    <td class="px-4 py-3 border-r dark:border-gray-700 font-mono text-sm">
                                        {{ $lesson->start_date }} <br>تا<br> {{ $lesson->end_date }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700 font-bold text-green-600">
                                        {{ $lesson->passing_score }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700 text-center">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('lessons.show', $lesson->id) }}"
                                                class="text-blue-600 font-bold hover:underline">زانیاری</a>

                                            <!-- ناوچەی مۆدڵی وانەکان -->
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
                                                            <div x-show="openEditModal"
                                                                x-transition:enter="ease-out duration-300"
                                                                x-transition:enter-start="opacity-0"
                                                                x-transition:enter-end="opacity-100"
                                                                x-transition:leave="ease-in duration-200"
                                                                x-transition:leave-start="opacity-100"
                                                                x-transition:leave-end="opacity-0"
                                                                @click="openEditModal = false"
                                                                class="fixed inset-0 bg-gray-900 bg-opacity-75 backdrop-blur-sm transition-opacity"
                                                                aria-hidden="true"></div>
                                                            <span
                                                                class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                                aria-hidden="true">&#8203;</span>

                                                            <div x-show="openEditModal"
                                                                x-transition:enter="ease-out duration-300"
                                                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                                x-transition:leave="ease-in duration-200"
                                                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                                class="inline-block relative align-bottom bg-white dark:bg-gray-800 rounded-2xl text-right overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full border border-gray-200 dark:border-gray-700">
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
                                                                            class="grid grid-cols-1 md:grid-cols-2 gap-5 text-right">
                                                                            <div><x-input-label
                                                                                    :value="__('ناوی وانە')" /><x-text-input
                                                                                    name="name" type="text"
                                                                                    class="mt-1 block w-full"
                                                                                    value="{{ $lesson->name }}"
                                                                                    required /></div>
                                                                            <div>
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
                                                                            <div><x-input-label
                                                                                    :value="__('کاتی وانە')" /><x-text-input
                                                                                    name="schedule" type="text"
                                                                                    class="mt-1 block w-full"
                                                                                    value="{{ $lesson->schedule }}"
                                                                                    required /></div>
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
                                    <td colspan="5" class="px-4 py-5 text-center font-bold text-gray-500">هیچ
                                        وانەیەکی بەردەست نییە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- خشتەی دووەم: وانە کۆتایی هاتووەکان -->
            <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg opacity-80">
                <h3 class="text-lg font-black text-gray-700 dark:text-gray-300 mb-4 flex items-center gap-2">🔴 وانە
                    کۆتایی هاتووەکان</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-right text-gray-500 dark:text-gray-400 border dark:border-gray-700">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3 border-r dark:border-gray-600">ناوی وانە</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">مامۆستا</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">کۆتایی هاتووە لە</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600 text-center">کردارەکان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($finishedLessons as $lesson)
                                <tr class="border-b dark:border-gray-700">
                                    <td
                                        class="px-4 py-3 border-r dark:border-gray-700 font-bold text-gray-700 dark:text-gray-300">
                                        {{ $lesson->name }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700">
                                        {{ $lesson->teacher->full_name }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700 font-mono text-sm">
                                        {{ $lesson->end_date }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700 text-center">
                                        <a href="{{ route('lessons.show', $lesson->id) }}"
                                            class="text-blue-600 font-bold hover:underline">زانیاری ورد</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-5 text-center">هیچ وانەیەکی کۆتایی هاتوو نییە.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
