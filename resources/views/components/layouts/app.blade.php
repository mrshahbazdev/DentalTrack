<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DentalTrack Scanner</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2563eb">
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 antialiased">
    <div class="fixed top-3 right-3 z-50 flex gap-1 bg-white/90 backdrop-blur rounded-lg shadow px-2 py-1">
        <a href="?lang=de" class="text-xs font-semibold px-2 py-1 rounded {{ app()->getLocale() === 'de' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">DE</a>
        <a href="?lang=en" class="text-xs font-semibold px-2 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">EN</a>
        <a href="?lang=ur" class="text-xs font-semibold px-2 py-1 rounded {{ app()->getLocale() === 'ur' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">اردو</a>
    </div>
    {{ $slot }}
    @livewireScripts
</body>
</html>
