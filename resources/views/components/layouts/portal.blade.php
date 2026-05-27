<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ur' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('portal.title') }} - DentalTrack</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="text-xl font-bold text-gray-900">DentalTrack</span>
            </div>
            <div class="flex gap-2">
                <a href="{{ url('/track?lang=en') }}" class="text-sm px-2 py-1 rounded {{ app()->getLocale() === 'en' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">EN</a>
                <a href="{{ url('/track?lang=ur') }}" class="text-sm px-2 py-1 rounded {{ app()->getLocale() === 'ur' ? 'bg-blue-100 text-blue-700' : 'text-gray-500 hover:text-gray-700' }}">اردو</a>
            </div>
        </div>
    </nav>
    <main class="max-w-4xl mx-auto px-4 py-8">
        {{ $slot }}
    </main>
</body>
</html>
