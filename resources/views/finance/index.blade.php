<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2"
            dir="rtl">
            💰 بەشی دارایی و پێشینەکان
        </h2>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- ئامارەکان -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-b-4 border-blue-500 transform transition hover:-translate-y-1">
                    <p class="text-gray-500 dark:text-gray-400 font-bold text-lg mb-2">کۆی گشتی بەشداربووان</p>
                    <p class="text-4xl font-black text-blue-600 font-mono">{{ $totalEnrolled }}</p>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-b-4 border-green-500 transform transition hover:-translate-y-1">
                    <p class="text-gray-500 dark:text-gray-400 font-bold text-lg mb-2">ئەوانەی پارەیان داوە</p>
                    <p class="text-4xl font-black text-green-600 font-mono">{{ $totalPaid }}</p>
                </div>
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-b-4 border-red-500 transform transition hover:-translate-y-1">
                    <p class="text-gray-500 dark:text-gray-400 font-bold text-lg mb-2">ئەوانەی پارەیان نەداوە</p>
                    <p class="text-4xl font-black text-red-600 font-mono">{{ $totalUnpaid }}</p>
                </div>
            </div>

            <!-- فلتەر و گەڕان -->
            <div
                class="bg-white dark:bg-gray-800 p-4 rounded-xl shadow-md border border-gray-200 dark:border-gray-700 mt-8">
                <form method="GET" action="{{ route('finance.index') }}" x-data="{ submitForm() { $el.submit(); } }"
                    class="flex flex-wrap items-end gap-6">

                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">فلتەر بەپێی
                            وانە:</label>
                        <select name="lesson_id" x-on:change="submitForm"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500">
                            <option value="">هەموو وانەکان</option>
                            @foreach ($lessons as $lesson)
                                <option value="{{ $lesson->id }}"
                                    {{ request('lesson_id') == $lesson->id ? 'selected' : '' }}>
                                    {{ $lesson->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1">دۆخی
                            پارەدان:</label>
                        <select name="payment_status" x-on:change="submitForm"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500">
                            <option value="">هەمووی</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>پارەی
                                داوە</option>
                            <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>پارەی
                                نەداوە</option>
                        </select>
                    </div>

                    @if (request('lesson_id') || request('payment_status'))
                        <div>
                            <a href="{{ route('finance.index') }}"
                                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md font-bold hover:bg-gray-300 transition-colors">
                                ✖ پاککردنەوەی فلتەر
                            </a>
                        </div>
                    @endif
                </form>
            </div>

            <!-- خشتەی داتاکان -->
            <div
                class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-900">
                            <tr>
                                <th
                                    class="px-6 py-4 text-start text-md font-bold text-gray-700 dark:text-gray-300 border-r dark:border-gray-600">
                                    ناوی خوێندکار</th>
                                <th
                                    class="px-6 py-4 text-start text-md font-bold text-gray-700 dark:text-gray-300 border-r dark:border-gray-600">
                                    ناوی وانە</th>
                                <th
                                    class="px-6 py-4 text-start text-md font-bold text-gray-700 dark:text-gray-300 border-r dark:border-gray-600">
                                    بەرواری بەشداریکردن</th>
                                <th
                                    class="px-6 py-4 text-start text-md font-bold text-gray-700 dark:text-gray-300 border-r dark:border-gray-600">
                                    دۆخی پارەدان</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($transactions as $tx)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-md font-bold text-gray-900 dark:text-white border-r dark:border-gray-700">
                                        {{ $tx->student_name }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-md text-gray-700 dark:text-gray-300 border-r dark:border-gray-700">
                                        {{ $tx->lesson_name }}</td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-md font-mono text-gray-600 dark:text-gray-400 border-r dark:border-gray-700">
                                        {{ \Carbon\Carbon::parse($tx->enroll_date)->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center border-r dark:border-gray-700">
                                        @if ($tx->is_paid)
                                            <span
                                                class="px-4 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-green-100 text-green-800">پارەی
                                                داوە ✅</span>
                                        @else
                                            <span
                                                class="px-4 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-red-100 text-red-800">نەیداوە
                                                ❌</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 font-bold">هیچ
                                        داتایەک نەدۆزرایەوە.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $transactions->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
