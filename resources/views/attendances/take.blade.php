<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center" dir="rtl">
            <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                خشتەی ئامادەبوونی وانەی: <span class="text-blue-600">{{ $lesson->name }}</span>
            </h2>
            <a href="{{ route('attendances.index') }}"
                class="py-2 px-5 rounded-xl border bg-gray-600 hover:bg-gray-700 text-white font-bold transition-all shadow-md">&larr;
                گەڕانەوە</a>
        </div>
    </x-slot>

    <!-- سکریپت بۆ ئاگادارکردنەوەی خێرا (Toast) کاتێک سەیڤ دەبێت -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            window.Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                background: document.documentElement.classList.contains('dark') ? '#374151' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });
        });
    </script>

    @php
        $kurdishDays = [
            'Saturday' => 'شەممە',
            'Sunday' => 'یەکشەممە',
            'Monday' => 'دووشەممە',
            'Tuesday' => 'سێشەممە',
            'Wednesday' => 'چوارشەممە',
            'Thursday' => 'پێنجشەممە',
            'Friday' => 'هەینی',
        ];
    @endphp

    <div class="py-12" dir="rtl">
        <div class="max-w-[98%] mx-auto sm:px-6 lg:px-8 space-y-6">

            <div
                class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col relative">

                <div
                    class="p-4 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                    <p class="font-bold text-gray-700 dark:text-gray-300">
                        👨‍🎓 ژمارەی خوێندکار: <span class="text-blue-600">{{ $students->count() }}</span>
                    </p>
                    <p class="text-sm font-bold text-gray-500">
                        تێبینی: هەر گۆڕانکارییەک بکەیت ڕاستەوخۆ خۆی سەیڤ دەبێت. ئەم پەڕەیە بۆ وەرگرتنی غیابات و
                        سەیرکردنەوەشیەتی بە یەکەوە.
                    </p>
                </div>

                <div class="overflow-x-auto relative" style="max-height: 70vh;">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-100 dark:bg-gray-900 sticky top-0 z-20">
                            <tr>
                                <th
                                    class="px-6 py-4 text-start text-sm font-black text-gray-800 dark:text-gray-200 border-l dark:border-gray-600 sticky right-0 z-30 bg-gray-100 dark:bg-gray-900 shadow-[inset_1px_0_0_0_rgba(0,0,0,0.1)]">
                                    ناوی خوێندکار
                                </th>

                                @forelse($dates as $date)
                                    <th
                                        class="px-2 py-3 text-center text-xs font-bold text-gray-700 dark:text-gray-300 border-l dark:border-gray-600 whitespace-nowrap min-w-[200px]">
                                        <div class="flex flex-col items-center gap-1">
                                            @php
                                                $dayEn = \Carbon\Carbon::parse($date)->format('l');
                                            @endphp
                                            <span class="text-sm">{{ $kurdishDays[$dayEn] }}</span>
                                            <span
                                                class="text-blue-600 dark:text-blue-400 font-mono text-[11px]">{{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</span>
                                        </div>
                                    </th>
                                @empty
                                    <th class="px-6 py-4 text-center text-gray-500">هیچ بەروارێک نەدۆزرایەوە</th>
                                @endforelse
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($students as $student)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">

                                    <td
                                        class="px-6 py-3 whitespace-nowrap font-bold text-gray-900 dark:text-white border-l dark:border-gray-700 sticky right-0 z-10 bg-white dark:bg-gray-800 shadow-[inset_1px_0_0_0_rgba(0,0,0,0.05)]">
                                        {{ $student->full_name }}
                                    </td>

                                    @foreach ($dates as $date)
                                        @php
                                            $recordKey = $student->id . '_' . $date;
                                            $currentStatus = isset($attendances[$recordKey])
                                                ? $attendances[$recordKey]->status
                                                : '';
                                            $currentNote = isset($attendances[$recordKey])
                                                ? addslashes($attendances[$recordKey]->notes)
                                                : '';
                                        @endphp

                                        <td class="px-2 py-3 border-l dark:border-gray-700 text-center"
                                            x-data="{
                                                status: '{{ $currentStatus }}',
                                                note: '{{ $currentNote }}',
                                                loading: false,

                                                updateStatus(newStatus) {
                                                    if (this.loading || this.status === newStatus) return;

                                                    if (newStatus === 'مۆڵەت') {
                                                        Swal.fire({
                                                            title: 'هۆکاری مۆڵەت',
                                                            input: 'text',
                                                            inputPlaceholder: 'بۆ نموونە: نەخۆشە...',
                                                            showCancelButton: true,
                                                            confirmButtonText: 'تۆمارکردن',
                                                            cancelButtonText: 'پاشگەزبوونەوە',
                                                            inputValidator: (value) => {
                                                                if (!value) return 'پێویستە هۆکاری مۆڵەتەکە بنووسیت!'
                                                            }
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                this.sendAjax(newStatus, result.value);
                                                            }
                                                        });
                                                    } else {
                                                        this.sendAjax(newStatus, null);
                                                    }
                                                },

                                                sendAjax(newStatus, newNote) {
                                                    this.loading = true;
                                                    fetch('{{ route('attendances.ajaxStore', $lesson->id) }}', {
                                                            method: 'POST',
                                                            headers: {
                                                                'Content-Type': 'application/json',
                                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                            },
                                                            body: JSON.stringify({
                                                                student_id: {{ $student->id }},
                                                                date: '{{ $date }}',
                                                                status: newStatus,
                                                                notes: newNote
                                                            })
                                                        })
                                                        .then(res => res.json())
                                                        .then(data => {
                                                            if (data.success) {
                                                                this.status = newStatus;
                                                                this.note = newNote;
                                                                window.Toast.fire({ icon: 'success', title: 'سەیڤ کرا' });
                                                            }
                                                            this.loading = false;
                                                        })
                                                        .catch(err => {
                                                            this.loading = false;
                                                            window.Toast.fire({ icon: 'error', title: 'هەڵەیەک ڕوویدا' });
                                                        });
                                                },

                                                showNote() {
                                                    if (this.note) {
                                                        Swal.fire({
                                                            title: 'هۆکاری مۆڵەت',
                                                            text: this.note,
                                                            icon: 'info',
                                                            confirmButtonText: 'داخستن'
                                                        });
                                                    }
                                                }
                                            }">

                                            <div class="flex items-center justify-center gap-1.5 opacity-100 transition-opacity relative"
                                                :class="{ 'opacity-50 pointer-events-none': loading }">

                                                <button @click="updateStatus('ئامادە')"
                                                    :class="status === 'ئامادە' ? 'bg-green-500 text-white shadow-md' :
                                                        'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-green-100 hover:text-green-700'"
                                                    class="px-3 py-1.5 rounded-lg font-bold text-[11px] transition-all w-[65px] border border-transparent">
                                                    هاتوو
                                                </button>

                                                <button @click="updateStatus('نەهاتوو')"
                                                    :class="status === 'نەهاتوو' ? 'bg-red-500 text-white shadow-md' :
                                                        'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-red-100 hover:text-red-700'"
                                                    class="px-3 py-1.5 rounded-lg font-bold text-[11px] transition-all w-[65px] border border-transparent">
                                                    نەهاتوو
                                                </button>

                                                <div class="relative">
                                                    <button @click="updateStatus('مۆڵەت')"
                                                        :class="status === 'مۆڵەت' ? 'bg-yellow-500 text-white shadow-md' :
                                                            'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-yellow-100 hover:text-yellow-700'"
                                                        class="px-3 py-1.5 rounded-lg font-bold text-[11px] transition-all w-[65px] border border-transparent pr-4">
                                                        مۆڵەت
                                                    </button>

                                                    <!-- ئایکۆنی پیشاندانی هۆکاری مۆڵەتەکە -->
                                                    <button x-show="status === 'مۆڵەت' && note" @click="showNote()"
                                                        title="هۆکارەکەی بخوێنەوە"
                                                        class="absolute -top-1.5 -right-1.5 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-full w-5 h-5 flex items-center justify-center text-[10px] shadow-sm transform transition-transform hover:scale-110">
                                                        💬
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                            @if ($students->isEmpty())
                                <tr>
                                    <td colspan="{{ count($dates) + 1 }}"
                                        class="px-6 py-8 text-center text-red-500 font-bold">
                                        هیچ خوێندکارێک بەشدار نییە لەم وانەیەدا.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
