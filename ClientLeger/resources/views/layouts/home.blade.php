<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@yield('title', 'Fast Sushi')</title>


        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
            <script src="https://cdn.tailwindcss.com"></script>
        @endif


        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" rel="stylesheet" />


        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-[#403D39] text-[#FFFCF2] min-h-screen flex flex-col">
        @if(session('success'))
        <div id="success-notification" class="fixed top-4 right-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg z-50 transform transition-transform duration-500 ease-in-out">
            <div class="flex items-center">
                <div class="mr-2">
                    <i class="fas fa-check-circle"></i>
                </div>
                <p>{{ session('success') }}</p>
                <button onclick="document.getElementById('success-notification').style.display = 'none'" class="ml-4 text-green-700 hover:text-green-900">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <script>
            setTimeout(() => {
                const notification = document.getElementById('success-notification');
                if (notification) {
                    notification.style.display = 'none';
                }
            }, 5000);
        </script>
        @endif

        @include('module.header')

        <main class="flex-grow main-class">
            @yield('content')
        </main>

        @include('module.footer')

        @stack('scripts')
    </body>
</html> 