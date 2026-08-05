<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo.png') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia(
                '(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="font-sans antialiased bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <script>
        var themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
        var themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && document.documentElement
                .classList.contains('dark'))) {
            themeToggleLightIcon?.classList.remove('hidden');
        } else {
            themeToggleDarkIcon?.classList.remove('hidden');
        }

        var themeToggleBtn = document.getElementById('theme-toggle');

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                themeToggleDarkIcon.classList.toggle('hidden');
                themeToggleLightIcon.classList.toggle('hidden');

                if (localStorage.getItem('color-theme')) {
                    if (localStorage.getItem('color-theme') === 'light') {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    }
                } else {
                    if (document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('color-theme', 'light');
                    } else {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('color-theme', 'dark');
                    }
                }
            });
        }
    </script>

    <!-- کۆدی سویت ئەلێرت بۆ پەیامەکان -->
    @if (session('success'))
        <script type="module">
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'سەرکەوتوو بوو!',
                    text: "{{ session('success') }}",
                    icon: 'success',
                    confirmButtonText: 'باشە',
                    confirmButtonColor: '#3085d6',
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
                    confirmButtonText: 'باشە',
                    confirmButtonColor: '#d33',
                });
            });
        </script>
    @endif

    <!-- فەنکشنی گشتی بۆ SweetAlert لەکاتی سڕینەوە و ئاگادارکردنەوە -->
    <script type="module">
        window.confirmAction = function(event, form, title, text, icon = 'warning', confirmButtonText =
            'بەڵێ، جێبەجێی بکە!', confirmButtonColor = '#d33') {
            event.preventDefault(); // ڕاگرتنی فۆڕمەکە
            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: confirmButtonColor,
                cancelButtonColor: '#3085d6',
                confirmButtonText: confirmButtonText,
                cancelButtonText: 'پاشگەزبوونەوە'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // ناردنی فۆڕمەکە ئەگەر دوگمەی بەڵێ داگیرا
                }
            });
        }
    </script>
</body>

</html>
