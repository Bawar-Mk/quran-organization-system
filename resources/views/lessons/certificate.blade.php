<!DOCTYPE html>
<html lang="ku" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بڕوانامە - {{ $student->full_name }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* هێنانە ناوەوەی ئەو فۆنتانەی کە لە فۆڵدەری public/fonts دانراون بە شێوەیەکی داینامیک */
        @foreach ($availableFonts as $font)
            @font-face {
                font-family: '{{ $font['name'] }}';
                src: url('{{ asset('fonts/' . $font['file']) }}') format('truetype');
            }
        @endforeach

        body {
            background-color: #e2e8f0;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-thumb {
            background: #94a3b8;
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }

            body,
            html {
                background-color: white !important;
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                overflow: hidden !important;
            }

            .no-print {
                display: none !important;
            }

            .print-wrapper {
                display: block !important;
                padding: 0 !important;
                margin: 0 !important;
                height: auto !important;
                width: 100% !important;
                background: none !important;
                align-items: flex-start !important;
                justify-content: flex-start !important;
                overflow: visible !important;
            }

            .print-container {
                box-shadow: none !important;
                width: 297mm !important;
                height: 210mm !important;
                margin: 0 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                transform: none !important;
            }
        }
    </style>
</head>

<body x-data="certificateEditor()" class="flex h-screen overflow-hidden text-gray-800">

    <aside class="w-[380px] bg-white border-l border-gray-200 shadow-2xl z-50 flex flex-col no-print h-full shrink-0">
        <div class="p-5 bg-blue-700 text-white text-center shadow-md relative">
            <h2 class="text-xl font-black mb-1">کۆنترۆڵی بڕوانامە</h2>
            <p class="text-[11px] text-blue-200 font-bold">بۆ هەر دەقێک ڕێکخستنی جیاواز هەیە</p>
        </div>

        <div class="flex-grow overflow-y-auto p-4 space-y-3 bg-gray-50">

            <details class="bg-white border border-gray-200 rounded-lg group shadow-sm overflow-hidden" open>
                <summary
                    class="p-3 font-bold text-sm cursor-pointer hover:bg-gray-50 list-none flex justify-between items-center bg-gray-100 text-blue-800">
                    <span>🖼️ وێنەکان (باکگراوند و لۆگۆ)</span>
                    <span class="group-open:rotate-180 transition-transform">▼</span>
                </summary>
                <div class="p-3 border-t border-gray-200 space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">وێنەی باکگراوند (A4)</label>
                        <input type="file" accept="image\*" @change="handleBgUpload"
                            class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:bg-blue-100 file:text-blue-700 cursor-pointer border border-gray-200 rounded">
                        <button x-show="bgImage" @click="bgImage = ''"
                            class="mt-1 text-[10px] font-bold text-red-500 hover:underline">✖ لابردنی باکگراوند</button>
                    </div>

                    <div class="pt-2 border-t border-gray-100 space-y-3">
                        <div>
                            <label class="flex justify-between text-xs font-bold text-gray-700 mb-1"><span>قەبارەی
                                    لۆگۆ:</span><span x-text="elements.logo.size + 'px'"
                                    class="text-blue-600 font-mono"></span></label>
                            <input type="range" x-model="elements.logo.size" min="20" max="400"
                                class="w-full h-1.5 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                        </div>
                        <div>
                            <label class="flex justify-between text-xs font-bold text-gray-700 mb-1"><span>ڕاست و چەپ
                                    (X):</span><span x-text="elements.logo.x + 'px'"
                                    class="text-blue-600 font-mono"></span></label>
                            <input type="range" x-model="elements.logo.x" min="-400" max="400"
                                class="w-full h-1.5 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                        </div>
                        <div>
                            <label class="flex justify-between text-xs font-bold text-gray-700 mb-1"><span>سەرەوە و
                                    خوارەوە (Y):</span><span x-text="elements.logo.y + 'px'"
                                    class="text-blue-600 font-mono"></span></label>
                            <input type="range" x-model="elements.logo.y" min="-400" max="400"
                                class="w-full h-1.5 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                        </div>

                        <button type="button" @click="resetLogo()"
                            class="w-full bg-gray-200 hover:bg-gray-300 text-gray-700 text-[11px] font-bold py-1.5 rounded transition-colors shadow-sm">
                            🔄 گەڕاندنەوەی لۆگۆ بۆ سەنتەر
                        </button>
                    </div>
                </div>
            </details>

            <template x-for="(el, key) in textElements" :key="key">
                <details class="bg-white border border-gray-200 rounded-lg group shadow-sm overflow-hidden">
                    <summary
                        class="p-3 font-bold text-sm cursor-pointer hover:bg-gray-50 list-none flex justify-between items-center bg-gray-100 text-gray-800">
                        <span x-text="el.label"></span>
                        <span class="group-open:rotate-180 transition-transform">▼</span>
                    </summary>
                    <div class="p-3 border-t border-gray-200 space-y-4">

                        <div x-show="el.hasText">
                            <label class="block text-xs font-bold text-gray-700 mb-1">دەقەکە بگۆڕە:</label>
                            <input type="text" x-model="elements[key].text"
                                class="w-full border-gray-300 rounded text-xs p-1.5 focus:ring-1 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">فۆنت:</label>
                            <select x-model="elements[key].font"
                                class="w-full border-gray-300 rounded text-xs p-1.5 focus:ring-1 focus:ring-blue-500">
                                @if (count($availableFonts) > 0)
                                    @foreach ($availableFonts as $font)
                                        <option value="'{{ $font['name'] }}', sans-serif">{{ $font['name'] }}</option>
                                    @endforeach
                                @else
                                    <option value="sans-serif">هیچ فۆنتێک نەدۆزرایەوە</option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label
                                class="flex justify-between text-xs font-bold text-gray-700 mb-1"><span>قەبارە:</span><span
                                    x-text="elements[key].size + 'px'" class="text-blue-600 font-mono"></span></label>
                            <input type="range" x-model="elements[key].size" min="10" max="250"
                                class="w-full h-1 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                        </div>

                        <div>
                            <label class="flex justify-between text-xs font-bold text-gray-700 mb-1"><span>ڕاست و چەپ
                                    (X):</span><span x-text="elements[key].x + 'px'"
                                    class="text-blue-600 font-mono"></span></label>
                            <input type="range" x-model="elements[key].x" min="-500" max="500"
                                class="w-full h-1 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                        </div>

                        <div>
                            <label class="flex justify-between text-xs font-bold text-gray-700 mb-1"><span>سەرەوە و نزمی
                                    (Y):</span><span x-text="elements[key].y + 'px'"
                                    class="text-blue-600 font-mono"></span></label>
                            <input type="range" x-model="elements[key].y" min="-400" max="600"
                                class="w-full h-1 bg-gray-200 rounded-lg appearance-none cursor-pointer accent-blue-600">
                        </div>

                        <div class="flex items-center gap-2">
                            <label class="text-xs font-bold text-gray-700">ڕەنگ:</label>
                            <input type="color" x-model="elements[key].color"
                                class="w-8 h-8 rounded cursor-pointer border-0 p-0 shadow-sm">
                            <span x-text="elements[key].color"
                                class="text-[10px] font-mono text-gray-500 uppercase"></span>
                        </div>

                        <div class="flex justify-between items-center pt-2 border-t border-gray-100">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" x-model="elements[key].show"
                                    class="rounded text-blue-600 border-gray-300 focus:ring-0">
                                <span class="text-xs font-bold text-gray-700">پیشاندانی ئەم دەقە</span>
                            </label>

                            <button type="button" @click="resetPosition(key)"
                                class="bg-gray-200 hover:bg-gray-300 text-gray-700 text-[10px] font-bold py-1 px-2 rounded transition-colors shadow-sm">
                                🔄 سەنتەر
                            </button>
                        </div>
                    </div>
                </details>
            </template>

            <!-- خەزنکردن و هێنانەوەی تێمپلەیت لە داتابەیس -->
            <details class="bg-yellow-50 border border-yellow-200 rounded-lg group shadow-sm overflow-hidden">
                <summary
                    class="p-3 font-bold text-sm cursor-pointer hover:bg-yellow-100 list-none flex justify-between items-center text-yellow-800">
                    <span>💾 خەزنکردن و تێمپلەیتەکان</span>
                    <span class="group-open:rotate-180 transition-transform">▼</span>
                </summary>
                <div class="p-3 border-t border-yellow-200 space-y-2">
                    <p class="text-[10px] text-yellow-700 font-bold mb-2">دەتوانیت دیزاین و ڕێکخستنەکانت بە هەمیشەیی لە
                        داتابەیس سەیڤ بکەیت.</p>

                    <button type="button" @click="savePreset()"
                        class="w-full bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-bold py-2 rounded transition-colors shadow-sm">
                        تۆمارکردنی ئەم دیزاینە
                    </button>

                    <div class="flex gap-2 mt-2">
                        <button type="button" @click="loadPreset()"
                            class="w-2/3 bg-white border border-yellow-500 text-yellow-700 hover:bg-yellow-100 text-xs font-bold py-2 rounded transition-colors shadow-sm">
                            هێنانەوەی دیزاین
                        </button>
                        <button type="button" @click="deletePreset()"
                            class="w-1/3 bg-white border border-red-500 text-red-600 hover:bg-red-50 text-xs font-bold py-2 rounded transition-colors shadow-sm">
                            سڕینەوە
                        </button>
                    </div>
                </div>
            </details>

        </div>

        <div class="p-5 bg-white border-t border-gray-200 flex gap-2 shrink-0">
            <button onclick="window.print()"
                class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded shadow transition-colors text-sm flex items-center justify-center gap-1.5">
                🖨️ پرینتکردن
            </button>
            <a href="{{ route('lessons.show', $lesson->id) }}"
                class="px-4 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2.5 rounded shadow-sm transition-colors text-sm flex items-center justify-center">
                گەڕانەوە
            </a>
        </div>
    </aside>

    <main class="print-wrapper flex-1 overflow-auto bg-gray-300 relative flex items-center justify-center p-8">
        <div
            class="print-container bg-white w-[297mm] h-[210mm] shadow-2xl relative overflow-hidden shrink-0 flex flex-col items-center justify-center">

            <template x-if="bgImage">
                <img :src="bgImage" class="absolute inset-0 w-full h-full object-cover z-0">
            </template>

            <template x-if="!bgImage">
                <div class="absolute inset-0 z-0">
                    <div class="absolute inset-0 opacity-5 pointer-events-none flex items-center justify-center">
                        <svg class="w-[800px] h-[800px] text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z">
                            </path>
                        </svg>
                    </div>
                    <div
                        class="absolute inset-4 border-[12px] border-double border-yellow-500/70 rounded-2xl pointer-events-none">
                    </div>
                    <div class="absolute inset-6 border-2 border-yellow-600/50 rounded-xl pointer-events-none"></div>
                </div>
            </template>

            <div
                class="relative z-10 w-full h-full flex flex-col items-center justify-center p-8 text-center leading-tight">

                <div :style="'transform: translate(' + elements.logo.x + 'px, ' + elements.logo.y + 'px)'"
                    class="mb-4 transition-transform duration-100 flex justify-center w-full">
                    <img src="{{ asset('logo.png') }}" :style="'height: ' + elements.logo.size + 'px'"
                        class="object-contain transition-all">
                </div>

                <template x-if="elements.sysName.show">
                    <h1 :style="'font-family:' + elements.sysName.font + '; font-size:' + elements.sysName.size + 'px; color:' +
                        elements.sysName.color + '; transform: translate(' + elements.sysName.x + 'px, ' + elements
                        .sysName.y + 'px)'"
                        class="font-black tracking-wider transition-all drop-shadow-sm mb-4 whitespace-nowrap">
                        <span x-text="elements.sysName.text"></span>
                    </h1>
                </template>

                <template x-if="!bgImage">
                    <div class="w-64 h-1 bg-gradient-to-r from-transparent via-yellow-500 to-transparent my-2"></div>
                </template>

                <template x-if="elements.title.show">
                    <h2 :style="'font-family:' + elements.title.font + '; font-size:' + elements.title.size + 'px; color:' +
                        elements.title.color + '; transform: translate(' + elements.title.x + 'px, ' + elements.title
                        .y + 'px)'"
                        class="font-black tracking-wide transition-all drop-shadow-sm mb-4 whitespace-nowrap">
                        <span x-text="elements.title.text"></span>
                    </h2>
                </template>

                <template x-if="elements.student.show">
                    <h3 :style="'font-family:' + elements.student.font + '; font-size:' + elements.student.size + 'px; color:' +
                        elements.student.color + '; transform: translate(' + elements.student.x + 'px, ' + elements
                        .student.y + 'px)'"
                        class="font-black transition-all drop-shadow-md mb-6 whitespace-nowrap">
                        {{ $student->full_name }}
                    </h3>
                </template>

                <template x-if="elements.info.show">
                    <p :style="'font-family:' + elements.info.font + '; font-size:' + elements.info.size + 'px; color:' + elements
                        .info.color + '; transform: translate(' + elements.info.x + 'px, ' + elements.info.y + 'px)'"
                        class="font-bold max-w-4xl mx-auto leading-relaxed transition-all px-4">
                        بەشداریکردنی لە خولی <span class="font-black">«{{ $lesson->name }}»</span> کە لەلایەن مامۆستا
                        <span class="font-black">({{ $lesson->teacher->full_name }})</span> وترایەوە، بە نمرەی <span
                            class="font-black font-mono" dir="ltr">{{ $enrollment->pivot->score }}%</span>.
                    </p>
                </template>

            </div>
        </div>
    </main>

    <script>
        function certificateEditor() {
            // دیاریکردنی یەکەم فۆنتی بەردەست وەک فۆنتی بنچینەیی ئەگەر هەبوو
            const defaultFont = @json(count($availableFonts) > 0 ? "'" . $availableFonts[0]['name'] . "', sans-serif" : 'sans-serif');

            return {
                bgImage: '{{ $lesson->certificate_template ? asset('storage/' . $lesson->certificate_template) : '' }}',

                textElements: {
                    sysName: {
                        label: '📝 دەقی ١: ناوی سیستەم',
                        hasText: true
                    },
                    title: {
                        label: '📝 دەقی ٢: تایتڵی شەهادە',
                        hasText: true
                    },
                    student: {
                        label: '🎓 دەقی ٣: ناوی خوێندکار',
                        hasText: false
                    },
                    info: {
                        label: 'ℹ️ دەقی ٤: زانیاری وانە',
                        hasText: false
                    },
                },

                elements: {
                    logo: {
                        x: 0,
                        y: 0,
                        size: 112
                    },
                    sysName: {
                        text: "سیستەمی بەڕێوەبردن",
                        font: defaultFont,
                        size: 30,
                        color: "#1f2937",
                        x: 0,
                        y: 0,
                        show: true
                    },
                    title: {
                        text: "بڕوانامەی ڕێزلێنان",
                        font: defaultFont,
                        size: 55,
                        color: "#1e40af",
                        x: 0,
                        y: 0,
                        show: true
                    },
                    student: {
                        font: defaultFont,
                        size: 60,
                        color: "#111827",
                        x: 0,
                        y: 0,
                        show: true
                    },
                    info: {
                        font: defaultFont,
                        size: 24,
                        color: "#374151",
                        x: 0,
                        y: 0,
                        show: true
                    }
                },

                handleBgUpload(e) {
                    if (e.target.files[0]) this.bgImage = URL.createObjectURL(e.target.files[0]);
                },

                resetPosition(key) {
                    this.elements[key].x = 0;
                    this.elements[key].y = 0;
                },

                resetLogo() {
                    this.elements.logo.x = 0;
                    this.elements.logo.y = 0;
                    this.elements.logo.size = 112;
                },

                // خەزنکردن بۆ داتابەیس بە بێ کێشەی CSRF لە ڕێگەی fetch API
                savePreset() {
                    Swal.fire({
                        title: 'ناوی تێمپلەیتەکە بنووسە',
                        input: 'text',
                        inputPlaceholder: 'بۆ نموونە: دیزاینی خولی هاوینە',
                        showCancelButton: true,
                        confirmButtonText: 'خەزنکردن',
                        cancelButtonText: 'پاشگەزبوونەوە'
                    }).then((result) => {
                        if (result.isConfirmed && result.value) {
                            fetch('{{ route('presets.store') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        name: result.value,
                                        data: this.elements
                                    })
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if (data.success) {
                                        Swal.fire('سەرکەوتوو بوو', 'دیزاینەکە بە هەمیشەیی خەزن کرا!',
                                            'success');
                                    }
                                }).catch(() => Swal.fire('هەڵە', 'کێشەیەک ڕوویدا لە کاتی خەزنکردندا', 'error'));
                        }
                    });
                },

                // هێنانەوە لە داتابەیس
                loadPreset() {
                    fetch('{{ route('presets.index') }}')
                        .then(res => res.json())
                        .then(presets => {
                            if (presets.length === 0) {
                                Swal.fire('هەڵە', 'هیچ دیزاینێکی خەزنکراو بوونی نییە!', 'error');
                                return;
                            }

                            // دروستکردنی ئۆبجێکتی هەڵبژاردنەکان بۆ مۆدڵەکە
                            let options = {};
                            presets.forEach(p => options[p.id] = p.name);

                            Swal.fire({
                                title: 'کام دیزاینەت دەوێت بیهێنیتەوە؟',
                                input: 'select',
                                inputOptions: options,
                                showCancelButton: true,
                                confirmButtonText: 'هێنانەوە',
                                cancelButtonText: 'پاشگەزبوونەوە'
                            }).then((result) => {
                                if (result.isConfirmed && result.value) {
                                    let selected = presets.find(p => p.id == result.value);
                                    // تێکەڵکردنی داتای ئێستا لەگەڵ داتای خەزنکراو
                                    this.elements = {
                                        ...this.elements,
                                        ...selected.data
                                    };
                                    Swal.fire('سەرکەوتوو بوو', 'دیزاینەکە بە سەرکەوتوویی جێبەجێ کرا!',
                                        'success');
                                }
                            });
                        });
                },

                // سڕینەوە لە داتابەیس
                deletePreset() {
                    fetch('{{ route('presets.index') }}')
                        .then(res => res.json())
                        .then(presets => {
                            if (presets.length === 0) {
                                Swal.fire('هەڵە', 'هیچ دیزاینێکی خەزنکراو بوونی نییە!', 'error');
                                return;
                            }

                            let options = {};
                            presets.forEach(p => options[p.id] = p.name);

                            Swal.fire({
                                title: 'کام دیزاینەت دەوێت بیسڕیتەوە؟',
                                input: 'select',
                                inputOptions: options,
                                showCancelButton: true,
                                confirmButtonText: 'سڕینەوە',
                                confirmButtonColor: '#ef4444',
                                cancelButtonText: 'پاشگەزبوونەوە'
                            }).then((result) => {
                                if (result.isConfirmed && result.value) {
                                    let deleteUrl = '/certificate-presets/' + result.value;
                                    fetch(deleteUrl, {
                                            method: 'DELETE',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'Accept': 'application/json',
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                            }
                                        })
                                        .then(res => res.json())
                                        .then(data => {
                                            if (data.success) {
                                                Swal.fire('سڕایەوە', 'تێمپلەیتەکە بە هەمیشەیی سڕایەوە!',
                                                    'success');
                                            }
                                        }).catch(() => Swal.fire('هەڵە', 'کێشەیەک ڕوویدا لە کاتی سڕینەوەدا',
                                            'error'));
                                }
                            });
                        });
                }
            }
        }
    </script>
</body>

</html>
