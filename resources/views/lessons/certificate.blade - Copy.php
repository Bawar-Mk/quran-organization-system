<!DOCTYPE html>
<html lang="ku" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بڕوانامەی دەرچوون - {{ $student->full_name }}</title>
    <!-- بانگکردنی تەیلویند بۆ دیزاین -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;700;800&display=swap');

        body {
            font-family: 'Noto Naskh Arabic', sans-serif;
            background-color: #f3f4f6;
        }

        /* ڕێکخستنی شاشە بۆ پرینتکردن */
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }

            body {
                background-color: white;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            .print-container {
                box-shadow: none !important;
                width: 100% !important;
                height: 100vh !important;
                margin: 0 !important;
                page-break-after: avoid;
            }
        }

        .certificate-border {
            background-image: repeating-linear-gradient(45deg, #fbbf24 25%, transparent 25%, transparent 75%, #fbbf24 75%, #fbbf24), repeating-linear-gradient(45deg, #fbbf24 25%, #ffffff 25%, #ffffff 75%, #fbbf24 75%, #fbbf24);
            background-position: 0 0, 10px 10px;
            background-size: 20px 20px;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">

    <!-- دوگمەکانی سەرەوە کە لە کاتی پرینتدا دیار نامێنن -->
    <div class="fixed top-5 right-5 flex gap-3 no-print z-50">
        <button onclick="window.print()"
            class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg flex items-center gap-2 transition-transform transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z">
                </path>
            </svg>
            پرینتکردن
        </button>
        <a href="{{ route('lessons.show', $lesson->id) }}"
            class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg shadow-lg flex items-center gap-2 transition-transform transform hover:scale-105">
            گەڕانەوە
        </a>
    </div>

    <!-- چوارچێوەی بڕوانامەکە -->
    <div
        class="print-container bg-white w-[297mm] h-[210mm] shadow-2xl relative overflow-hidden flex items-center justify-center p-8 mx-auto my-8">

        <!-- باکگراوندی دیزاین (تەنها بۆ جوانی) -->
        <div class="absolute inset-0 opacity-5 pointer-events-none flex items-center justify-center">
            <svg class="w-[800px] h-[800px] text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z">
                </path>
            </svg>
        </div>

        <!-- بۆردەری ئاڵتوونی -->
        <div class="absolute inset-4 border-[12px] border-double border-yellow-500/70 rounded-2xl pointer-events-none">
        </div>
        <div class="absolute inset-6 border-2 border-yellow-600/50 rounded-xl pointer-events-none"></div>

        <div class="relative z-10 w-full h-full flex flex-col items-center text-center pt-8">

            <!-- لۆگۆ یان ناوی ڕێکخراو -->
            <div class="mb-4">
                <div
                    class="w-24 h-24 mx-auto bg-blue-50 border-2 border-blue-200 rounded-full flex items-center justify-center mb-3 shadow-inner">
                    <span class="text-4xl">🎓</span>
                </div>
                <h1 class="text-2xl font-black text-gray-800 tracking-wider">سیستەمی بەڕێوەبردن</h1>
                <p class="text-sm font-bold text-gray-500 mt-1">بڕوانامەی بەشداریکردن و دەرچوون</p>
            </div>

            <div class="w-64 h-1 bg-gradient-to-r from-transparent via-yellow-500 to-transparent my-6"></div>

            <!-- دەقی بڕوانامەکە -->
            <h2 class="text-5xl font-black text-blue-800 my-6 tracking-wide drop-shadow-sm">بڕوانامەی ڕێزلێنان</h2>

            <p class="text-xl text-gray-700 font-bold mt-4">
                ئەم بڕوانامەیە دەبەخشرێت بە بەڕێز /
            </p>

            <h3
                class="text-4xl font-black text-gray-900 my-6 underline decoration-yellow-400 decoration-4 underline-offset-8">
                {{ $student->full_name }}
            </h3>

            <p class="text-xl text-gray-700 font-bold leading-relaxed max-w-3xl mx-auto">
                لە پای بەشداریکردنی کارا و دەرچوونی بە سەرکەوتوویی لە خولی
                <span class="text-blue-700 font-black">«{{ $lesson->name }}»</span>
                کە لەلایەن مامۆستا <span class="font-black">({{ $lesson->teacher->full_name }})</span> وترایەوە، بە
                بەدەستهێنانی نمرەی
                <span class="text-2xl font-black text-green-600 font-mono"
                    dir="ltr">{{ $enrollment->pivot->score }}%</span>.
                <span class="text-2xl font-black text-green-600 font-mono" dir="ltr">{{ $student->pivot->score }}%</span>.
            </p>

            <p class="text-lg text-gray-600 font-bold mt-6">
                هیوای سەرکەوتن و پێشکەوتنی زیاتری بۆ دەخوازین لە بوارەکەیدا.
            </p>

            <!-- بەشی واژۆکان لە خوارەوە -->
            <div class="mt-auto w-full flex justify-between px-20 pb-10">
                <div class="text-center">
                    <p class="text-lg font-bold text-gray-800 mb-8">واژۆی مامۆستای وانە</p>
                    <div class="w-40 h-px bg-gray-400 mx-auto"></div>
                </div>

                <div class="text-center flex flex-col justify-end">
                    <!-- مۆر یان کیو ئار کۆد -->
                    <div
                        class="w-20 h-20 border-4 border-red-500/30 rounded-full flex items-center justify-center transform -rotate-12 opacity-80 mx-auto mb-2">
                        <span class="text-red-500/50 font-black text-sm">پەسەندکراوە</span>
                    </div>
                    <p class="text-sm font-bold text-gray-500 font-mono" dir="ltr">Date:
                        {{ \Carbon\Carbon::now()->format('Y-m-d') }}</p>
                </div>

                <div class="text-center">
                    <p class="text-lg font-bold text-gray-800 mb-8">واژۆی بەڕێوەبەر</p>
                    <div class="w-40 h-px bg-gray-400 mx-auto"></div>
                </div>
            </div>

        </div>
    </div>

</body>

</html>
