<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight" dir="rtl">
            بەشی ئامادەبوون (غیابات)
        </h2>
    </x-slot>

    <div class="py-12" dir="rtl">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 border-t-4 border-blue-500">
                <h3 class="text-lg font-black text-gray-900 dark:text-gray-100 mb-4">وانە بەردەستەکان بۆ وەرگرتنی
                    ئامادەبوون</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($lessons as $lesson)
                        <div
                            class="bg-gray-50 dark:bg-gray-700 rounded-xl p-5 border border-gray-200 dark:border-gray-600 shadow-sm hover:shadow-md transition-shadow">
                            <h4 class="text-xl font-bold text-blue-700 dark:text-blue-400 mb-2">{{ $lesson->name }}</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-300 font-bold mb-1">
                                👨‍🏫 مامۆستا: {{ $lesson->teacher->full_name }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 font-bold mb-4">
                                🕒 کات: {{ $lesson->schedule }}
                            </p>
                            <a href="{{ route('attendances.take', $lesson->id) }}"
                                class="block text-center w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-lg transition-colors">
                                وەرگرتنی ئامادەبوون
                            </a>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400 font-bold">
                            هیچ وانەیەکی چالاک بەردەست نییە.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
