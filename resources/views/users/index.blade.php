<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight" dir="rtl">
            {{ __('بەڕێوەبردنی بەکارهێنەران') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ showEditModal: false, editData: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- فۆڕمی زیادکردن لەگەڵ سیستەمی پشکنینی خێرا -->
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg border-t-4 border-purple-500" dir="rtl">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center gap-2">
                    <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                        </path>
                    </svg>
                    زیادکردنی بەکارهێنەری نوێ
                </h3>

                <form method="POST" action="{{ route('users.store') }}"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 items-end" x-data="{ newName: '', newUsername: '', newPassword: '', selectedRole: 'user', selectedTeacher: '' }">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ناوی تەواو *</label>
                        <input type="text" name="name" x-model="newName" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ناوی بەکارهێنەر *</label>
                        <input type="text" name="username" x-model="newUsername" required
                            class="w-full text-left rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm transition-colors" dir="ltr">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">وشەی تێپەڕ *</label>
                        <input type="password" name="password" x-model="newPassword" required
                            class="w-full text-left rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm transition-colors" dir="ltr">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">پلە *</label>
                        <select name="role" x-model="selectedRole" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm font-bold text-purple-600 transition-colors">
                            <option value="user">بەکارهێنەری ئاسایی</option>
                            <option value="teacher">مامۆستا</option>
                            <option value="admin">بەڕێوەبەر</option>
                        </select>
                    </div>

                    <!-- کۆمبۆ بۆکسی دیاریکردنی مامۆستا کە تەنها ئەگەر پلەکە مامۆستا بێت دەردەکەوێت -->
                    <div x-show="selectedRole === 'teacher'" x-transition>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">هەڵبژاردنی مامۆستا *</label>
                        <select name="teacher_id" x-model="selectedTeacher" :required="selectedRole === 'teacher'"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm transition-colors">
                            <option value="">-- مامۆستا هەڵبژێرە --</option>
                            @foreach ($teachers as $t)
                                <option value="{{ $t->id }}">{{ $t->full_name }} {{ $t->user_id ? '(پێشتر بەستراوە)' : '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- بەشی مەرجەکان و دوگمەی تۆمارکردن -->
                    <div class="lg:col-span-5 flex flex-col md:flex-row justify-between items-center mt-6 border-t border-gray-200 dark:border-gray-700 pt-5">
                        <div class="flex flex-col md:flex-row gap-4 text-sm font-bold w-full md:w-auto mb-4 md:mb-0">
                            <span class="text-gray-600 dark:text-gray-400">مەرجەکان:</span>
                            <span :class="newName.trim().length > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400'"
                                class="transition-colors duration-300 flex items-center gap-1">
                                <span x-text="newName.trim().length > 0 ? '✅' : '❌'"></span> ناوی تەواو
                            </span>
                            <span :class="newUsername.trim().length > 0 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400'"
                                class="transition-colors duration-300 flex items-center gap-1">
                                <span x-text="newUsername.trim().length > 0 ? '✅' : '❌'"></span> ناوی بەکارهێنەر
                            </span>
                            <span :class="newPassword.length >= 4 ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400'"
                                class="transition-colors duration-300 flex items-center gap-1">
                                <span x-text="newPassword.length >= 4 ? '✅' : '❌'"></span> وشەی تێپەڕ
                            </span>
                            <span x-show="selectedRole === 'teacher'" :class="selectedTeacher !== '' ? 'text-green-600 dark:text-green-400' : 'text-red-500 dark:text-red-400'"
                                class="transition-colors duration-300 flex items-center gap-1">
                                <span x-text="selectedTeacher !== '' ? '✅' : '❌'"></span> هەڵبژاردنی مامۆستا
                            </span>
                        </div>

                        <button type="submit"
                            :disabled="newName.trim().length === 0 || newUsername.trim().length === 0 || newPassword.length < 4 || (selectedRole === 'teacher' && selectedTeacher === '')"
                            :class="(newName.trim().length === 0 || newUsername.trim().length === 0 || newPassword.length < 4 || (selectedRole === 'teacher' && selectedTeacher === '')) ?
                            'bg-gray-400 dark:bg-gray-600 cursor-not-allowed opacity-75' :
                            'bg-purple-600 hover:bg-purple-700 cursor-pointer'"
                            class="text-white font-bold py-3 px-8 rounded-lg shadow-md transition-all duration-300">
                            تۆمارکردن
                        </button>
                    </div>
                </form>
            </div>

            <!-- بەشی گەڕان -->
            <div class="relative" dir="rtl">
                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" id="searchInput" placeholder="گەڕان بۆ ناو یان ناوی بەکارهێنەر..."
                    class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm px-4 py-4 pr-12 text-lg transition-colors">
            </div>

            <!-- خشتەی بەکارهێنەران -->
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" dir="rtl">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-4 text-start text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">ناو</th>
                                <th class="px-6 py-4 text-start text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">ناوی بەکارهێنەر</th>
                                <th class="px-6 py-4 text-start text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">پلە</th>
                                <th class="px-6 py-4 text-start text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">دۆخ</th>
                                <th class="px-6 py-4 text-start text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">بەرواری دروستکردن</th>
                                <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">کردارەکان</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700" id="tableBody">
                            @foreach ($users as $u)
                                <tr class="searchable-row hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900 dark:text-gray-100">
                                        {{ $u->name }}
                                        @if (auth()->id() === $u->id)
                                            <span class="text-xs bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 px-2 py-1 rounded-full mr-2">خۆت</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-400 font-mono" dir="ltr">
                                        {{ $u->username }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($u->role === 'admin')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">بەڕێوەبەر</span>
                                        @elseif ($u->role === 'teacher')
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">مامۆستا</span>
                                            @if($u->teacher)
                                                <span class="text-xs text-gray-500 block mt-1">بەستراوە بە: {{ $u->teacher->full_name }}</span>
                                            @endif
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">بەکارهێنەری ئاسایی</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($u->is_active ?? true)
                                            <span class="px-2 py-1 inline-flex text-xs font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">چالاک</span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">ناچالاک</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                        {{ $u->created_at->format('Y-m-d') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium space-x-3 space-x-reverse">
                                        <button @click="editData = {{ $u }}; editData.teacher_id = {{ $u->teacher ? $u->teacher->id : 'null' }}; showEditModal = true"
                                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-bold bg-indigo-50 dark:bg-indigo-900/20 px-3 py-1.5 rounded-lg transition-colors">دەستکاریکردن</button>

                                        @if (auth()->id() !== $u->id)
                                            <form action="{{ route('users.toggleStatus', $u) }}" method="POST" class="inline-block">
                                                @csrf @method('PATCH')
                                                <button type="submit"
                                                    class="font-bold px-3 py-1.5 rounded-lg transition-colors {{ $u->is_active ?? true ? 'text-orange-600 bg-orange-50 dark:bg-orange-900/20 hover:text-orange-900' : 'text-emerald-600 bg-emerald-50 dark:bg-emerald-900/20 hover:text-emerald-900' }}">
                                                    {{ $u->is_active ?? true ? 'ناچالاک بکە' : 'چالاک بکە' }}
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            <tr id="no-results-row" style="display: none;">
                                <td colspan="6" class="px-6 py-8 text-center text-red-500 dark:text-red-400 font-bold">هیچ ئەنجامێک نەدۆزرایەوە.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- پەنجەرەی دەستکاریکردن -->
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div x-show="showEditModal" @click="showEditModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>
                <div x-show="showEditModal"
                    class="inline-block w-full max-w-4xl p-6 my-8 overflow-hidden text-right align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl border-t-4 border-purple-500 relative z-10"
                    dir="rtl">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">دەستکاریکردنی بەکارهێنەر</h3>

                    <form method="POST" x-bind:action="'{{ url('users') }}/' + editData.id">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ناوی تەواو *</label>
                                <input type="text" name="name" x-model="editData.name" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ناوی بەکارهێنەر *</label>
                                <input type="text" name="username" x-model="editData.username" required dir="ltr"
                                    class="w-full text-left rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">وشەی تێپەڕ (خاڵی جێیبهێڵە ئەگەر نایگۆڕیت)</label>
                                <input type="password" name="password" dir="ltr"
                                    class="w-full text-left rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-purple-500"
                                    placeholder="********">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">پلە *</label>
                                <select name="role" x-model="editData.role" required
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-purple-500 focus:ring-purple-500">
                                    <option value="user">بەکارهێنەری ئاسایی</option>
                                    <option value="teacher">مامۆستا</option>
                                    <option value="admin">بەڕێوەبەر</option>
                                </select>
                            </div>
                            <!-- کۆمبۆ بۆکسی دیاریکردنی مامۆستا لە کاتی دەستکاریکردن -->
                            <div class="md:col-span-2" x-show="editData.role === 'teacher'">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">هەڵبژاردنی مامۆستا بۆ ئەم هەژمارە *</label>
                                <select name="teacher_id" x-model="editData.teacher_id" :required="editData.role === 'teacher'"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-purple-500 focus:ring-purple-500 shadow-sm transition-colors">
                                    <option value="">-- مامۆستا هەڵبژێرە --</option>
                                    @foreach ($teachers as $t)
                                        <option value="{{ $t->id }}">{{ $t->full_name }} {{ $t->user_id && $t->user_id != 'editData.id' ? '(پێشتر بەستراوە)' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" @click="showEditModal = false"
                                class="bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-bold py-2 px-6 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">پاشگەزبوونەوە</button>
                            <button type="submit"
                                class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-6 rounded-lg">نوێکردنەوە</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <!-- سکریپتەکان -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    let filter = this.value.toLowerCase();
                    let rows = document.querySelectorAll('.searchable-row');
                    let hasVisibleRows = false;
                    rows.forEach(row => {
                        if (row.innerText.toLowerCase().includes(filter)) {
                            row.style.display = '';
                            hasVisibleRows = true;
                        } else {
                            row.style.display = 'none';
                        }
                    });
                    document.getElementById('no-results-row').style.display = (hasVisibleRows || rows
                        .length === 0) ? 'none' : '';
                });
            }

            function getSwalColors() {
                const isDark = document.documentElement.classList.contains('dark');
                return {
                    bg: isDark ? '#1f2937' : '#ffffff',
                    text: isDark ? '#f3f4f6' : '#111827'
                };
            }

            @if (session('success'))
                const colors = getSwalColors();
                window.Swal.fire({
                    title: 'سەرکەوتوو بوو!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'باشە',
                    confirmButtonColor: '#9333ea',
                    background: colors.bg,
                    color: colors.text,
                    timer: 3000
                });
            @endif

            @if (session('error'))
                const colorsErr = getSwalColors();
                window.Swal.fire({
                    title: 'هەڵە ڕوویدا!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'باشە',
                    confirmButtonColor: '#ef4444',
                    background: colorsErr.bg,
                    color: colorsErr.text,
                });
            @endif

            @if ($errors->any())
                const colorsVal = getSwalColors();
                window.Swal.fire({
                    title: 'هەڵەیەک هەیە!',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    icon: 'error',
                    confirmButtonText: 'تێگەیشتم',
                    confirmButtonColor: '#d33',
                    background: colorsVal.bg,
                    color: colorsVal.text,
                });
            @endif
        });
    </script>
</x-app-layout>
