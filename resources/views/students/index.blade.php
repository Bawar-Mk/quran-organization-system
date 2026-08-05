<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('بەشی خوێندکاران') }}
        </h2>
    </x-slot>

    @if ($errors->any())
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'هەڵەیەک هەیە!',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    icon: 'error',
                    confirmButtonText: 'تێگەیشتم',
                    confirmButtonColor: '#d33',
                });
            });
        </script>
    @endif

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- فۆڕمی زیادکردن -->
            <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4 border-b pb-2 dark:border-gray-700">
                    تۆمارکردنی خوێندکاری نوێ</h3>
                <form method="POST" action="{{ route('students.store') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    @csrf
                    <div>
                        <x-input-label for="full_name" :value="__('ناوی تەواوی')" />
                        <x-text-input id="full_name" name="full_name" type="text" class="mt-1 block w-full"
                            value="{{ old('full_name') }}" required />
                    </div>
                    <div>
                        <x-input-label for="gender" :value="__('ڕەگەز')" />
                        <select name="gender" id="gender"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                            required>
                            <option value="نێر" {{ old('gender') == 'نێر' ? 'selected' : '' }}>نێر</option>
                            <option value="مێ" {{ old('gender') == 'مێ' ? 'selected' : '' }}>مێ</option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="date_of_birth" :value="__('بەرواری لەدایکبوون')" />
                        <x-text-input id="date_of_birth" name="date_of_birth" type="date" class="mt-1 block w-full"
                            value="{{ old('date_of_birth') }}" required />
                    </div>
                    <div>
                        <x-input-label for="education_level" :value="__('ئاستی خوێندن')" />
                        <x-text-input id="education_level" name="education_level" type="text"
                            class="mt-1 block w-full" value="{{ old('education_level') }}" />
                    </div>
                    <div>
                        <x-input-label for="phone_number" :value="__('ژمارەی مۆبایل')" />
                        <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full"
                            value="{{ old('phone_number') }}" />
                    </div>
                    <div>
                        <x-input-label for="join_date" :value="__('بەرواری پەیوەندیکردن')" />
                        <x-text-input id="join_date" name="join_date" type="date" class="mt-1 block w-full"
                            value="{{ old('join_date') }}" required />
                    </div>
                    <div>
                        <x-input-label for="study_type" :value="__('جۆری خوێندن')" />
                        <select name="study_type" id="study_type"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                            required>
                            <option value="ئاسایی" {{ old('study_type') == 'ئاسایی' ? 'selected' : '' }}>ئاسایی</option>
                            <option value="ئۆنلاین" {{ old('study_type') == 'ئۆنلاین' ? 'selected' : '' }}>ئۆنلاین
                            </option>
                        </select>
                    </div>
                    <div>
                        <x-input-label for="marital_status" :value="__('باری خێزانداری')" />
                        <select name="marital_status" id="marital_status"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                            <option value="سەڵت" {{ old('marital_status') == 'سەڵت' ? 'selected' : '' }}>سەڵت
                            </option>
                            <option value="خێزاندار" {{ old('marital_status') == 'خێزاندار' ? 'selected' : '' }}>
                                خێزاندار</option>
                        </select>
                    </div>
                    <div class="lg:col-span-3">
                        <x-input-label for="address" :value="__('ناونیشان')" />
                        <x-text-input id="address" name="address" type="text" class="mt-1 block w-full"
                            value="{{ old('address') }}" />
                    </div>
                    <div class="flex justify-end pb-0.5">
                        <x-primary-button class="w-full justify-center">{{ __('تۆمارکردن') }}</x-primary-button>
                    </div>
                </form>
            </div>

            <!-- خشتەی خوێندکاران -->
            <div class="p-4 sm:p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <form method="GET" action="{{ route('students.index') }}" x-data="{ submitForm() { $el.submit(); } }"
                    class="mb-6 flex gap-4 items-end bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
                    <div class="flex-1 max-w-md">
                        <x-text-input name="search" type="text" placeholder="گەڕان بەپێی ناو یان مۆبایل..."
                            value="{{ request('search') }}" class="w-full" x-on:input.debounce.700ms="submitForm" />
                    </div>
                    <div>
                        <select name="study_type" x-on:change="submitForm"
                            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm text-sm">
                            <option value="هەمووی" {{ request('study_type') == 'هەمووی' ? 'selected' : '' }}>هەموو
                                جۆرەکان</option>
                            <option value="ئاسایی" {{ request('study_type') == 'ئاسایی' ? 'selected' : '' }}>ئاسایی
                            </option>
                            <option value="ئۆنلاین" {{ request('study_type') == 'ئۆنلاین' ? 'selected' : '' }}>ئۆنلاین
                            </option>
                        </select>
                    </div>
                </form>

                <div class="overflow-x-auto relative">
                    <table class="w-full text-right text-gray-500 dark:text-gray-400 border dark:border-gray-700">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-4 py-3 border-r dark:border-gray-600">ناوی تەواوی</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">مۆبایل</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">ئاستی خوێندن</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600">جۆری خوێندن</th>
                                <th class="px-4 py-3 border-r dark:border-gray-600 text-center">کردارەکان</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($students as $student)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-750">
                                    <td
                                        class="px-4 py-3 border-r dark:border-gray-700 font-bold text-gray-900 dark:text-white">
                                        {{ $student->full_name }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700 font-mono text-sm"
                                        dir="ltr">{{ $student->phone_number ?: '-' }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700">
                                        {{ $student->education_level ?: '-' }}</td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700">
                                        <span
                                            class="px-2 py-1 text-xs font-bold rounded-full {{ $student->study_type == 'ئاسایی' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                                            {{ $student->study_type }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 border-r dark:border-gray-700 text-center">
                                        <div class="flex items-center justify-center gap-3">
                                            <a href="{{ route('students.show', $student->id) }}"
                                                class="text-blue-600 dark:text-blue-400 font-bold hover:underline">زانیاری</a>

                                            <!-- ناوچەی مۆدڵ -->
                                            <div x-data="{ openEditModal: false }" class="inline">
                                                <button @click="openEditModal = true" type="button"
                                                    class="text-green-600 dark:text-green-400 font-bold hover:underline">گۆڕانکاری</button>

                                                <template x-teleport="body">
                                                    <div x-show="openEditModal" style="display: none;"
                                                        class="fixed inset-0 z-50 overflow-y-auto"
                                                        aria-labelledby="modal-title" role="dialog"
                                                        aria-modal="true" dir="rtl">
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
                                                                    action="{{ route('students.update', $student->id) }}">
                                                                    @csrf @method('PATCH')
                                                                    <div
                                                                        class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                                        <div
                                                                            class="flex justify-between items-center mb-5 border-b dark:border-gray-700 pb-3">
                                                                            <h3
                                                                                class="text-xl font-black text-gray-900 dark:text-white">
                                                                                ✏️ گۆڕانکاری لە: <span
                                                                                    class="text-blue-600">{{ $student->full_name }}</span>
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
                                                                            class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 text-right">
                                                                            <div><x-input-label
                                                                                    :value="__('ناوی تەواوی')" /><x-text-input
                                                                                    name="full_name" type="text"
                                                                                    class="mt-1 block w-full"
                                                                                    value="{{ $student->full_name }}"
                                                                                    required /></div>
                                                                            <div>
                                                                                <x-input-label :value="__('ڕەگەز')" />
                                                                                <select name="gender"
                                                                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                                                                    <option value="نێر"
                                                                                        {{ $student->gender == 'نێر' ? 'selected' : '' }}>
                                                                                        نێر</option>
                                                                                    <option value="مێ"
                                                                                        {{ $student->gender == 'مێ' ? 'selected' : '' }}>
                                                                                        مێ</option>
                                                                                </select>
                                                                            </div>
                                                                            <div><x-input-label
                                                                                    :value="__('بەرواری لەدایکبوون')" /><x-text-input
                                                                                    name="date_of_birth"
                                                                                    type="date"
                                                                                    class="mt-1 block w-full"
                                                                                    value="{{ $student->date_of_birth }}"
                                                                                    required /></div>
                                                                            <div><x-input-label
                                                                                    :value="__('ئاستی خوێندن')" /><x-text-input
                                                                                    name="education_level"
                                                                                    type="text"
                                                                                    class="mt-1 block w-full"
                                                                                    value="{{ $student->education_level }}" />
                                                                            </div>
                                                                            <div><x-input-label
                                                                                    :value="__('ژمارەی مۆبایل')" /><x-text-input
                                                                                    name="phone_number" type="text"
                                                                                    class="mt-1 block w-full text-left font-mono"
                                                                                    value="{{ $student->phone_number }}"
                                                                                    dir="ltr" /></div>
                                                                            <div><x-input-label
                                                                                    :value="__(
                                                                                        'بەرواری پەیوەندیکردن',
                                                                                    )" /><x-text-input
                                                                                    name="join_date" type="date"
                                                                                    class="mt-1 block w-full"
                                                                                    value="{{ $student->join_date }}"
                                                                                    required /></div>
                                                                            <div>
                                                                                <x-input-label :value="__('جۆری خوێندن')" />
                                                                                <select name="study_type"
                                                                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                                                                    <option value="ئاسایی"
                                                                                        {{ $student->study_type == 'ئاسایی' ? 'selected' : '' }}>
                                                                                        ئاسایی</option>
                                                                                    <option value="ئۆنلاین"
                                                                                        {{ $student->study_type == 'ئۆنلاین' ? 'selected' : '' }}>
                                                                                        ئۆنلاین</option>
                                                                                </select>
                                                                            </div>
                                                                            <div>
                                                                                <x-input-label :value="__('باری خێزانداری')" />
                                                                                <select name="marital_status"
                                                                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                                                                    <option value="سەڵت"
                                                                                        {{ $student->marital_status == 'سەڵت' ? 'selected' : '' }}>
                                                                                        سەڵت</option>
                                                                                    <option value="خێزاندار"
                                                                                        {{ $student->marital_status == 'خێزاندار' ? 'selected' : '' }}>
                                                                                        خێزاندار</option>
                                                                                </select>
                                                                            </div>
                                                                            <div class="lg:col-span-3"><x-input-label
                                                                                    :value="__('ناونیشان')" /><x-text-input
                                                                                    name="address" type="text"
                                                                                    class="mt-1 block w-full"
                                                                                    value="{{ $student->address }}" />
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="bg-gray-50 dark:bg-gray-700/30 px-4 py-4 sm:px-6 flex gap-3 justify-end border-t dark:border-gray-700 rounded-b-2xl">
                                                                        <button type="button"
                                                                            @click="openEditModal = false"
                                                                            class="px-5 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-bold hover:bg-gray-50 transition-colors">پاشگەزبوونەوە</button>
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

                                            <form action="{{ route('students.destroy', $student->id) }}"
                                                method="POST" class="inline"
                                                onsubmit="confirmAction(event, this, 'دڵنیایت لە سڕینەوەی ئەم خوێندکارە؟', 'بە سڕینەوەی، هەموو زانیاری و نمرەکانیشی دەسڕێتەوە!');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 dark:text-red-400 font-bold hover:underline">سڕینەوە</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-5 text-center font-bold text-gray-500">هیچ
                                        خوێندکارێک نەدۆزراوەتەوە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $students->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
