<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'RBA ESG - Oops')</title>
    
    <!-- Tailwind CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    
    @stack('styles')
</head>
<body class="bg-gray-300 text-black min-h-screen flex items-center justify-center p-5">
    <!-- Top Bar -->
    <div class="fixed top-0 left-0 right-0 z-40 bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="{{ route('home') }}" class="flex items-center group">
                    <div class="w-24 h-24 flex items-center justify-center transition-transform duration-200 group-hover:scale-105">
                        <img src="{{ companyLogo() }}" alt="{{ config('app.name', 'RBA') }}">
                    </div>
                </a>
                <a href="{{ route('home') }}" class="text-lg font-semibold hover:text-gray-600">← Back to Home</a>
            </div>
        </div>
    </div>
    
    <main class="min-h-screen flex items-center justify-center px-4 pt-24 pb-12">
        <div class="w-full max-w-md">
            @yield('content')
        </div>
    </main>
    
    @stack('scripts')
    
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('scripts')
</body>
</html>