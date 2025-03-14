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
        @include('module.header')

        <main class="flex-grow">
            @yield('content')
        </main>

        @include('module.footer')

        @stack('scripts')
    </body>
</html> 