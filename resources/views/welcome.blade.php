<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Academic Management') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-100 text-gray-900 antialiased">
        <main class="min-h-screen flex items-center justify-center px-4">
            <section class="w-full max-w-xl bg-white border border-gray-200 rounded-2xl shadow-sm p-8 text-center">
                <h1 class="text-3xl font-bold tracking-tight">Academic Management System</h1>
                <p class="mt-3 text-gray-600">
                    Simple portal for Admin, Teacher, and Student access.
                </p>

                <div class="mt-8 flex flex-col sm:flex-row sm:justify-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                            Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
                            Login
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-lg bg-gray-200 text-gray-800 font-semibold hover:bg-gray-300">
                                Register
                            </a>
                        @endif
                    @endauth
                </div>
            </section>
        </main>
    </body>
</html>
