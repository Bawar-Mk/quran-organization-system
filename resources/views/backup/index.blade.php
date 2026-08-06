<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight" dir="rtl">
            {{ __('بەڕێوەبردنی زانیارییە پاشەکەوتکراوەکان') }}
        </h2>
    </x-slot>

    <!-- دڵنیابوونەوە لە نیشاندانی نامەکان بە SweetAlert -->
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
    @if (session('error'))
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'هەڵەیەک ڕوویدا!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'تێگەیشتم',
                    confirmButtonColor: '#ef4444'
                });
            });
        </script>
    @endif

    <div class="py-12 w-full" x-data="backupManager()">
        <div class="w-full max-w-7xl mx-auto px-2 sm:px-4 space-y-8 relative">

            <!-- دابەشکردنی شاشەکە: ١ بەش بۆ فۆرم، ٢ بەش بۆ خشتەکە -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" dir="rtl">

                <!-- بەشی دروستکردنی باکئەپ (١ بەش لە ٣ بەش) -->
                <div
                    class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 border-t-4 border-blue-500 lg:col-span-1 h-fit">
                    <h3
                        class="text-xl font-bold text-blue-600 dark:text-blue-400 mb-6 border-b pb-2 dark:border-gray-700">
                        پاشەکەوتکردنی نوێ
                    </h3>
                    <form action="{{ route('backup.create') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-3">
                                <p class="font-bold text-gray-700 dark:text-gray-300">دیاریکردنی خشتەکان:</p>
                                <label
                                    class="flex items-center gap-2 cursor-pointer text-md text-blue-500 font-bold select-none">
                                    <input type="checkbox" x-model="selectAll"
                                        class="rounded text-blue-600 border-gray-300 dark:border-gray-600 focus:ring-0">
                                    هەمووی
                                </label>
                            </div>

                            <!-- ڕیزکردنی خشتەکان بە شێوازی تاگ -->
                            <div
                                class="max-h-52 overflow-y-auto bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg p-2.5 flex flex-wrap gap-1.5 content-start">
                                @foreach ($tables as $table)
                                    <label
                                        class="inline-flex items-center gap-1.5 cursor-pointer bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-400 px-2 py-1 rounded text-md transition-colors shadow-sm">
                                        <input type="checkbox" name="tables[]" value="{{ $table }}"
                                            x-bind:checked="selectAll"
                                            class="rounded text-blue-600 border-gray-300 dark:border-gray-600 w-3.5 h-3.5 focus:ring-0">
                                        <span
                                            class="text-gray-700 dark:text-gray-300 select-none text-sm font-mono">{{ $table }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-2 font-bold">* تێبینی: ئەگەر هیچت
                                دیاری نەکرد، خۆکارانە تەواوی داتابەیسەکە پاشەکەوت دەکرێت.</p>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-lg shadow-md transition-colors text-md">
                            پاشەکەوتکردنی زانیارییەکان
                        </button>
                    </form>
                </div>

                <!-- بەشی لیستی باکئەپەکان و گەڕاندنەوە (٢ بەش لە ٣ بەش) -->
                <div
                    class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 border-t-4 border-green-500 flex flex-col lg:col-span-2">
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 border-b dark:border-gray-700 pb-4 gap-4">
                        <h3 class="text-xl font-bold text-green-600 dark:text-green-400">لیستی پاشەکەوتەکان</h3>

                        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                            <!-- گەڕان بەپێی بەروار -->
                            <div class="flex items-center gap-2 flex-grow sm:flex-grow-0">
                                <label class="text-md font-bold text-gray-600 dark:text-gray-400">گەڕان:</label>
                                <input type="date" x-model="searchDate"
                                    class="rounded-lg border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-md px-2 py-1.5 w-full sm:w-auto">
                            </div>

                            <!-- دوگمەی کردنەوەی فۆڵدەرەکە -->
                            <form action="{{ route('backup.open_folder') }}" method="POST"
                                class="flex-grow sm:flex-grow-0">
                                @csrf
                                <button type="submit"
                                    class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1.5 px-3 rounded-lg shadow transition-colors text-md flex items-center justify-center gap-1.5">
                                    📂 کردنەوەی شوێنی فۆڵدەر
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="overflow-x-auto flex-grow min-h-[300px]">
                        <table class="w-full text-center border-collapse text-md sm:text-sm">
                            <thead class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold">
                                <tr>
                                    <th class="p-2.5 border dark:border-gray-600">ناوی فایل</th>
                                    <th class="p-2.5 border dark:border-gray-600">ناوەرۆک</th>
                                    <th class="p-2.5 border dark:border-gray-600">قەبارە</th>
                                    <th class="p-2.5 border dark:border-gray-600">بەروار و کات</th>
                                    <th class="p-2.5 border dark:border-gray-600">کردارەکان</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 dark:text-gray-300">
                                <template x-for="backup in paginatedBackups" :key="backup.name">
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 border-b dark:border-gray-700">
                                        <td class="p-2 border dark:border-gray-700 text-center text-xs font-bold text-blue-600 dark:text-blue-400 break-all max-w-[150px] font-mono"
                                            dir="ltr" x-text="backup.name"></td>

                                        <!-- ستوونی ناوەرۆکی خشتەکان -->
                                        <td class="p-2 border dark:border-gray-700 text-right" dir="rtl">
                                            <div class="flex flex-col items-start gap-1">
                                                <span x-show="backup.is_full"
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300 whitespace-nowrap">
                                                    تەواوی داتابەیسەکە
                                                </span>
                                                <span x-show="!backup.is_full"
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300 whitespace-nowrap">
                                                    خشتەی دیاریکراو (<span x-text="backup.tables_count"></span>)
                                                </span>
                                            </div>
                                        </td>

                                        <td class="p-2 border dark:border-gray-700 whitespace-nowrap text-xs font-mono"
                                            x-text="backup.size" dir="ltr"></td>
                                        <td class="p-2 border dark:border-gray-700 text-xs whitespace-nowrap font-mono"
                                            x-text="backup.date" dir="ltr"></td>
                                        <td class="p-2 border dark:border-gray-700 whitespace-nowrap">
                                            <div class="flex justify-center items-center gap-2">
                                                <form :id="'restoreForm_' + backup.name"
                                                    action="{{ route('backup.restore') }}" method="POST"
                                                    class="hidden">
                                                    @csrf
                                                    <input type="hidden" name="file_name" :value="backup.name">
                                                </form>

                                                <button type="button" @click="confirmRestore(backup.name)"
                                                    class="text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 hover:bg-green-100 dark:hover:bg-green-900/40 px-3 py-1.5 rounded font-bold text-xs transition-colors shadow-sm">
                                                    گەڕاندنەوە
                                                </button>

                                                <a :href="'{{ url('backup/download') }}/' + backup.name"
                                                    class="text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/40 px-3 py-1.5 rounded font-bold text-xs transition-colors shadow-sm">
                                                    داگرتن
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <tr x-show="filteredBackups.length === 0">
                                    <td colspan="5"
                                        class="p-8 border dark:border-gray-700 font-bold text-gray-500 dark:text-gray-400 text-center">
                                        هیچ پەڕگەیەک بوونی نییە بۆ ئەم بەروارە.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- بەشی پەڕەکان (Pagination) -->
                    <div class="mt-4 flex justify-between items-center pt-4 border-t dark:border-gray-700"
                        x-show="totalPages > 1">
                        <button type="button" @click="if(currentPage > 1) currentPage--" :disabled="currentPage === 1"
                            class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded font-bold text-sm disabled:opacity-50 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            پێشتر
                        </button>

                        <span class="text-sm font-bold text-gray-600 dark:text-gray-400" dir="rtl">
                            پەڕەی <span x-text="currentPage"></span> لە <span x-text="totalPages"></span>
                        </span>

                        <button type="button" @click="if(currentPage < totalPages) currentPage++"
                            :disabled="currentPage === totalPages"
                            class="px-3 py-1.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded font-bold text-sm disabled:opacity-50 hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                            دواتر
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('backupManager', () => ({
                selectAll: true,
                searchDate: '',
                currentPage: 1,
                perPage: 10,
                allBackups: @json($backups),

                init() {
                    this.$watch('searchDate', (value) => {
                        this.currentPage = 1;
                    });
                },

                get filteredBackups() {
                    let filtered = this.allBackups;
                    if (this.searchDate !== '') {
                        filtered = filtered.filter(b => b.date.startsWith(this.searchDate));
                    }
                    return filtered;
                },

                get paginatedBackups() {
                    let start = (this.currentPage - 1) * this.perPage;
                    return this.filteredBackups.slice(start, start + this.perPage);
                },

                get totalPages() {
                    return Math.max(1, Math.ceil(this.filteredBackups.length / this.perPage));
                },

                confirmRestore(fileName) {
                    Swal.fire({
                        title: 'دڵنیایت لە گەڕاندنەوەی زانیارییەکان؟',
                        text: "داتاکانی ناو ئەم پاشەکەوتە بۆ کۆتایی خشتەکانی ئێستات زیاد دەکرێن!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#ef4444',
                        confirmButtonText: 'بەڵێ، بیگەڕێنەوە',
                        cancelButtonText: 'پاشگەزبوونەوە',
                        background: document.documentElement.classList.contains('dark') ?
                            '#1f2937' : '#ffffff',
                        color: document.documentElement.classList.contains('dark') ? '#ffffff' :
                            '#000000',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('restoreForm_' + fileName).submit();
                        }
                    });
                }
            }));
        });
    </script>
</x-app-layout>
