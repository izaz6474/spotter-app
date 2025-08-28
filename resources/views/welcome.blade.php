<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Spotter</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex flex-col items-center justify-center min-h-screen p-6 lg:p-8">

    <!-- Welcome Text -->
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold text-gray-900 dark:text-white mb-4">
            Welcome to Spotter
        </h1>
        <p class="text-lg text-gray-600 dark:text-gray-400">
            Track your workouts and find your community
        </p>
    </div>

    <!-- Auth Buttons -->
    <div class="flex flex-col items-center gap-6 mt-8">
        @auth
            <a href="{{ url('/home') }}"
            class="w-64 py-2 border-2 border-indigo-600 text-indigo-600 font-bold rounded-xl text-lg 
                    text-center hover:bg-indigo-600 hover:text-white transition-colors duration-300">
                Home
            </a>
        @else
            <a href="{{ route('login') }}"
            class="w-64 py-2 border-2 border-indigo-600 text-indigo-600 font-bold rounded-xl text-lg 
                    text-center hover:bg-indigo-600 hover:text-white transition-colors duration-300">
                Log in
            </a>

            @if (Route::has('register'))
                <a href="{{ route('register') }}"
                class="w-64 py-2 border-2 border-indigo-600 text-indigo-600 font-bold rounded-xl text-lg 
                        text-center hover:bg-indigo-600 hover:text-white transition-colors duration-300">
                    Register
                </a>
            @endif
        @endauth
    </div>
</body>
</html>
