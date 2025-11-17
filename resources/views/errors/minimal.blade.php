<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>@yield('code') @yield('title')</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="antialiased bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 items-center justify-center gap-2 min-h-screen">
            <!-- Kiri: Code dan Message -->
            <div class="text-center md:text-left max-w-lg mx-auto">
                <h1 class="mb-2 text-9xl tracking-tight font-extrabold text-blue-600 leading-none">
                    @yield('code')
                </h1>
                <h2 class="mb-6 text-5xl tracking-tight font-extrabold text-blue-600 leading-tight">
                    @yield('message')
                </h2>

                <div class="flex justify-center md:justify-start space-x-3">
                    <a href="{{ url()->previous() }}"
                        class="inline-flex items-center px-6 py-3 text-lg font-semibold text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300">
                        <svg class="w-6 h-6 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" />
                        </svg>Kembali
                    </a>
                </div>
            </div>

            <!-- Kanan: Gambar (hanya muncul di md ke atas) -->
            <div class="hidden md:flex justify-center">
                <img src="{{ asset('assets/images/presapp-large.png') }}" alt="mockup"
                    class="w-[300px] md:w-[350px] lg:w-[400px] object-contain" />
            </div>
        </div>
    </div>
</body>






</html>
