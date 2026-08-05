<!DOCTYPE html>
<html lang="ckb" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بڕوانامە - {{ $student->full_name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ڕێکخستنی پەڕە بۆ کاتی پرینتکردن */
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }

            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background-color: white !important;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body class="bg-gray-200 flex items-center justify-center min-h-screen p-8">

    <!-- چوارچێوەی بڕوانامە -->
    <div
        class="bg-white w-[297mm] h-[210mm] p-16 shadow-2xl relative border-[16px] border-double border-blue-900 rounded-xl text-center flex flex-col justify-center bg-[url('https://www.transparenttextures.com/patterns/stardust.png')]">

        <h1 class="text-6xl font-black text-blue-900 mb-10 tracking-widest drop-shadow-sm">بڕوانامەی ڕێزلێنان</h1>

        <p class="text-2xl text-gray-700 mb-6 font-semibold">ئەم بڕوانامەیە بەخشرا بە خوێندکار:</p>

        <h2 class="text-5xl font-black text-gray-900 mb-8 border-b-2 border-gray-400 pb-4 inline-block mx-auto px-12">
            {{ $student->full_name }}</h2>

        <p class="text-xl text-gray-700 mb-8 leading-relaxed max-w-4xl mx-auto font-medium">
            لەپای بەشداریکردنی سەرکەوتووانە و تەواوکردنی خولی <span
                class="font-black text-blue-800 text-2xl">({{ $lesson->name }})</span>
            کە لەلایەن مامۆستا <span class="font-black text-gray-900 text-2xl">{{ $lesson->teacher->full_name }}</span>
            وە وترایەوە،
            لە ڕێکەوتی <span class="font-mono text-gray-800" dir="ltr">{{ $lesson->start_date }}</span> تا <span
                class="font-mono text-gray-800" dir="ltr">{{ $lesson->end_date }}</span>.
        </p>

        <div class="mb-12 inline-block mx-auto bg-gray-50 border border-gray-200 rounded-2xl px-8 py-4 shadow-inner">
            <p class="text-2xl text-gray-800 font-bold">
                کۆنمرەی بەدەستهاتوو: <span
                    class="font-black text-4xl text-green-700 font-mono tracking-wider">{{ $enrollment->pivot->score }}</span>
            </p>
        </div>

        <div class="flex justify-between items-end mt-auto px-20">
            <!-- واژووی مامۆستا -->
            <div class="text-center w-64">
                <p class="text-xl font-bold text-gray-700 mb-4">واژووی مامۆستا</p>
                <div class="w-full h-[2px] bg-gray-400 mx-auto"></div>
            </div>

            <!-- مۆر یان لۆگۆ -->
            <div
                class="w-40 h-40 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-full flex items-center justify-center border-4 border-dashed border-white outline outline-4 outline-yellow-600 shadow-xl shadow-yellow-600/20 transform -translate-y-4">
                <span
                    class="text-white font-black text-xl text-center leading-tight drop-shadow-md">لۆگۆی<br>ڕێکخراو</span>
            </div>

            <!-- بەروار -->
            <div class="text-center w-64">
                <p class="text-xl font-bold text-gray-700 mb-4">بەرواری دەرچوون</p>
                <p class="font-mono text-xl text-gray-900 mb-1">{{ \Carbon\Carbon::now()->format('Y-m-d') }}</p>
                <div class="w-full h-[2px] bg-gray-400 mx-auto"></div>
            </div>
        </div>
    </div>

    <!-- دوگمەی پرینت کردن (لە کاتی پرینت دیار نامێنێت) -->
    <button onclick="window.print()"
        class="no-print fixed bottom-8 right-8 bg-blue-600 text-white px-8 py-4 rounded-full shadow-2xl font-bold text-xl hover:bg-blue-700 transition-transform transform hover:scale-105 flex items-center gap-3 ring-4 ring-blue-300">
        🖨️ چاپکردنی بڕوانامە
    </button>

</body>

</html>
