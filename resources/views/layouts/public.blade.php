<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'E-Library') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 flex flex-col min-h-screen">

    <header class="bg-white shadow-md">
        <div class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}"
                class="text-3xl font-extrabold text-indigo-700 hover:text-indigo-900 transition duration-300 ease-in-out">
                {{ config('app.name', 'E-Library') }}
            </a>

            <nav class="flex items-center space-x-6">
                <a href="{{ url('/') }}"
                    class="text-gray-700 hover:text-indigo-700 font-medium transition duration-300 ease-in-out">Home</a>
                <a href="{{ url('/books') }}"
                    class="text-gray-700 hover:text-indigo-700 font-medium transition duration-300 ease-in-out">Books</a>
                <a href="{{ url('/about') }}"
                    class="text-gray-700 hover:text-indigo-700 font-medium transition duration-300 ease-in-out">About</a>
                <a href="{{ url('/contact') }}"
                    class="text-gray-700 hover:text-indigo-700 font-medium transition duration-300 ease-in-out">Contact</a>

                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="text-gray-700 hover:text-indigo-700 font-medium transition duration-300 ease-in-out">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit"
                            class="text-gray-700 hover:text-indigo-700 font-medium transition duration-300 ease-in-out">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="text-gray-700 hover:text-indigo-700 font-medium transition duration-300 ease-in-out">Login</a>
                    <a href="{{ route('register') }}"
                        class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300 ease-in-out shadow-sm">Register</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="flex-1 container mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="bg-white shadow mt-8">
        <div class="container mx-auto px-4 py-4 text-center text-gray-500">
            &copy; {{ date('Y') }} {{ config('app.name', 'E-Library') }}. All rights reserved.
        </div>
    </footer>

</body>

</html>
