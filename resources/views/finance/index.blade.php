<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2"
            dir="rtl">
            💰 بەشی دارایی و پێشینەکان
        </h2>
    </x-slot>

    @if (session('success'))
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'سەرکەوتوو بوو!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'باشە',
                    confirmButtonColor: '#10b981'
                });
            });
        </script>
    @endif

    <div class="py-12" dir="rtl" x-data="{ tab: 'transactions' }">
        <div class="max-w-[95%] mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- دوگمەکانی گۆڕینی تابەکان -->
            <div class="flex border-b border-gray-200 dark:border-gray-700">
                <button @click="tab = 'transactions'"
                    :class="tab === 'transactions' ? 'border-b-2 border-blue-500 text-blue-600 dark:text-blue-400 font-black' :
                        'text-gray-500 dark:text-gray-400 font-bold hover:text-gray-700'"
                    class="px-6 py-3 text-lg transition-colors">
                    داهات و خەرجی گشتی
                </button>
                <button @click="tab = 'students'"
                    :class="tab === 'students' ? 'border-b-2 border-blue-500 text-blue-600 dark:text-blue-400 font-black' :
                        'text-gray-500 dark:text-gray-400 font-bold hover:text-gray-700'"
                    class="px-6 py-3 text-lg transition-colors">
                    پارەدانی خوێندکاران
                </button>
            </div>

            <!-- ======================= تابی داهات و خەرجی گشتی ======================= -->
            <div x-show="tab === 'transactions'" class="space-y-6">
                <!-- ئامارە داراییەکان -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-b-4 border-green-500 transform transition hover:-translate-y-1">
                        <p class="text-gray-500 dark:text-gray-400 font-bold text-lg mb-2">کۆی داهات 🟢</p>
                        <p class="text-4xl font-black text-green-600 font-mono">{{ number_format($totalIncome, 0) }}
                            <span class="text-sm">دینار</span></p>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-b-4 border-red-500 transform transition hover:-translate-y-1">
                        <p class="text-gray-500 dark:text-gray-400 font-bold text-lg mb-2">کۆی خەرجی 🔴</p>
                        <p class="text-4xl font-black text-red-600 font-mono">{{ number_format($totalExpense, 0) }}
                            <span class="text-sm">دینار</span></p>
                    </div>
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border-b-4 border-blue-500 transform transition hover:-translate-y-1">
                        <p class="text-gray-500 dark:text-gray-400 font-bold text-lg mb-2">داهاتی پوخت (قازانج) 💎</p>
                        <p class="text-4xl font-black {{ $netProfit >= 0 ? 'text-blue-600' : 'text-red-500' }} font-mono"
                            dir="ltr">
                            {{ number_format($netProfit, 0) }} <span class="text-sm">دینار</span>
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- فۆڕمی زیادکردنی خەرجی/داهات -->
                    <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl p-6 lg:col-span-1 h-fit">
                        <h3
                            class="text-xl font-bold text-gray-800 dark:text-white border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            تۆمارکردنی مامەڵەی نوێ
                        </h3>
                        <form action="{{ route('finance.storeTransaction') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <x-input-label value="ناوی مامەڵە (وەک: کڕینی پەرتووک)" />
                                <x-text-input name="title" type="text" class="mt-1 w-full" required />
                            </div>
                            <div>
                                <x-input-label value="جۆری مامەڵە" />
                                <select name="type"
                                    class="mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500"
                                    required>
                                    <option value="income">داهات (وەرگرتنی پارە)</option>
                                    <option value="expense">خەرجی (ڕۆیشتنی پارە)</option>
                                </select>
                            </div>
                            <div>
                                <x-input-label value="بڕی پارە (بە دینار)" />
                                <x-text-input name="amount" type="number" min="0"
                                    class="mt-1 w-full font-mono text-left" dir="ltr" required />
                            </div>
                            <div>
                                <x-input-label value="بەروار" />
                                <x-text-input name="transaction_date" type="date" value="{{ date('Y-m-d') }}"
                                    class="mt-1 w-full font-mono" required />
                            </div>
                            <div>
                                <x-input-label value="تێبینی زیاتر (ئارەزوومەندانە)" />
                                <textarea name="notes" rows="2"
                                    class="mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500"></textarea>
                            </div>
                            <button type="submit"
                                class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow transition-colors">
                                تۆمارکردن
                            </button>
                        </form>
                    </div>

                    <!-- خشتەی مامەڵەکان -->
                    <div
                        class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl p-6 lg:col-span-2 overflow-hidden flex flex-col">
                        <div
                            class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3 mb-4">
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">لیستی پسوڵەکان</h3>
                            <form method="GET" action="{{ route('finance.index') }}" x-data="{ submit() { $el.submit(); } }">
                                <select name="type" x-on:change="submit"
                                    class="text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                    <option value="">هەموو مامەڵەکان</option>
                                    <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>تەنها
                                        داهاتەکان</option>
                                    <option value="expense" {{ request('type') == 'expense' ? 'selected' : '' }}>تەنها
                                        خەرجییەکان</option>
                                </select>
                            </form>
                        </div>

                        <div class="overflow-x-auto flex-grow">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-100 dark:bg-gray-900">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-start text-sm font-bold text-gray-700 dark:text-gray-300">
                                            مامەڵە</th>
                                        <th
                                            class="px-4 py-3 text-start text-sm font-bold text-gray-700 dark:text-gray-300">
                                            بڕی پارە</th>
                                        <th
                                            class="px-4 py-3 text-start text-sm font-bold text-gray-700 dark:text-gray-300">
                                            بەروار</th>
                                        <th
                                            class="px-4 py-3 text-start text-sm font-bold text-gray-700 dark:text-gray-300">
                                            تۆمارکەر</th>
                                        <th
                                            class="px-4 py-3 text-center text-sm font-bold text-gray-700 dark:text-gray-300">
                                            سڕینەوە</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($transactions as $trans)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                            <td class="px-4 py-3">
                                                <p class="font-bold text-gray-900 dark:text-white">{{ $trans->title }}
                                                </p>
                                                @if ($trans->notes)
                                                    <p class="text-xs text-gray-500">{{ $trans->notes }}</p>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 font-mono font-bold" dir="ltr">
                                                @if ($trans->type == 'income')
                                                    <span class="text-green-600">+
                                                        {{ number_format($trans->amount, 0) }}</span>
                                                @else
                                                    <span class="text-red-500">-
                                                        {{ number_format($trans->amount, 0) }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 font-mono text-sm text-gray-600 dark:text-gray-400">
                                                {{ $trans->transaction_date }}</td>
                                            <td class="px-4 py-3 text-sm font-bold text-gray-700 dark:text-gray-300">
                                                {{ $trans->user->name }}</td>
                                            <td class="px-4 py-3 text-center">
                                                <form action="{{ route('finance.destroyTransaction', $trans->id) }}"
                                                    method="POST" class="inline"
                                                    onsubmit="return confirm('دڵنیایت لە سڕینەوەی ئەم مامەڵەیە؟');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-500 hover:text-red-700 font-bold">❌</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 font-bold">
                                                هیچ مامەڵەیەک تۆمار نەکراوە.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- ======================= تابی پارەدانی خوێندکاران ======================= -->
            <div x-show="tab === 'students'" style="display: none;" class="space-y-6">
                <!-- ئامارەکان (هی خۆتە) -->
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

                <!-- فلتەر و گەڕان (هی خۆتە) -->
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
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                    پارەی داوە</option>
                                <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>
                                    پارەی نەداوە</option>
                            </select>
                        </div>

                        @if (request('lesson_id') || request('payment_status'))
                            <div>
                                <a href="{{ route('finance.index') }}"
                                    class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-md font-bold hover:bg-gray-300 transition-colors">
                                    ✖ پاککردنەوە
                                </a>
                            </div>
                        @endif
                    </form>
                </div>

                <!-- خشتەی داتاکان (هی خۆتە) -->
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
                                @forelse($studentPayments as $tx)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-md font-bold text-gray-900 dark:text-white border-r dark:border-gray-700">
                                            {{ $tx->student_name }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-md text-gray-700 dark:text-gray-300 border-r dark:border-gray-700">
                                            {{ $tx->lesson_name }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-md font-mono text-gray-600 dark:text-gray-400 border-r dark:border-gray-700">
                                            {{ \Carbon\Carbon::parse($tx->enroll_date)->format('Y-m-d') }}</td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-center border-r dark:border-gray-700">
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
                                            class="px-6 py-8 text-center text-gray-500 dark:text-gray-400 font-bold">
                                            هیچ داتایەک نەدۆزرایەوە.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $studentPayments->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
