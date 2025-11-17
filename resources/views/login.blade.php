<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/presapp-logo.png') }}" />

    @vite('resources/css/app.css')

    <style>
        .background-body {
            position: relative;
            overflow: hidden;
        }

        .background-image {
            background-image: url('{{ asset('assets/images/gdungjti.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(5px);
            position: absolute;
            inset: 0;
            z-index: -2;
        }

        .gray-overlay {
            background-color: rgba(139, 178, 255, 0.2);
            position: absolute;
            inset: 0;
            z-index: -1;
        }
    </style>
</head>

<body class="background-body">
    <div class="background-image"></div>
    <div class="gray-overlay"></div>

    <div class="min-h-screen w-full flex justify-center items-center text-gray-900 px-4 text-center">
        <div class="bg-white shadow-lg rounded-xl overflow-hidden flex w-full max-w-4xl">
            <div class="w-full md:w-1/2 p-8 space-y-6">
                <a href="/">
                    <button class="flex items-center text-blue-600 hover:text-blue-800 font-semibold">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path>
                        </svg>Kembali
                    </button>
                </a>
                <div class="text-center">
                    <img src="../assets/images/presapp-logo.png" alt="Logo" class="w-28 mx-auto mb-4" />
                    <h1 class="text-2xl font-bold text-indigo-600">Selamat Datang</h1>
                    <p class="text-sm text-gray-500">Silakan masuk untuk melanjutkan</p>
                </div>

                <form id="loginForm" onsubmit="return validateForm(event)" action="{{ route('custom.login') }}"
                    method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <input id="username" name="username" value="{{ old('username') }}"
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-300 placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            type="text" placeholder="Username" />
                        <p id="usernameError" class="text-red-500 text-sm mt-1 hidden">Username harus diisi.</p>
                    </div>

                    <div class="relative">
                        <input id="password" name="password"
                            class="w-full px-4 py-3 rounded-lg bg-gray-100 border border-gray-300 placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                            type="password" placeholder="Password" />
                        <button type="button" onclick="togglePassword()"
                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-indigo-600">
                            <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                        <p id="passwordError" class="text-red-500 text-sm mt-1 hidden">Password harus diisi.</p>
                    </div>

                    @if (session()->has('loginError'))
                        <div id="alert-2"
                            class="flex items-center p-3 bg-red-100 text-red-700 rounded-lg text-sm relative transition-opacity duration-500 ease-in-out"
                            role="alert">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                            </svg>
                            <span>{{ session('loginError') }}</span>
                            <button id="close-alert" class="ml-auto text-red-500 hover:text-red-700 focus:outline-none">
                                ✕
                            </button>
                        </div>
                    @endif

                    <button type="submit" id="btn-login"
                        class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg transition duration-300 flex justify-center items-center">
                        <span id="btn-login-text">Masuk</span>
                        <svg id="btn-login-spinner" class="hidden ml-2 w-5 h-5 animate-spin text-white" fill="none"
                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 108 8h-2a6 6 0 11-6-6z">
                            </path>
                        </svg>
                    </button>

                </form>
            </div>

            <div class="hidden md:block md:w-1/2 bg-indigo-100">
                <div class="h-full w-full bg-no-repeat bg-center bg-contain"
                    style="background-image: url('{{ asset('assets/images/login-image.png') }}');">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const alertBox = document.getElementById("alert-2");
            const closeBtn = document.getElementById("close-alert");

            if (alertBox && closeBtn) {
                function fadeOutAndRemove() {
                    alertBox.classList.add("opacity-0");
                    setTimeout(() => alertBox.remove(), 500);
                }

                closeBtn.addEventListener("click", fadeOutAndRemove);
                setTimeout(fadeOutAndRemove, 7000);
            }
        });

        function togglePassword() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');

            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.classList.add('text-indigo-600');
            } else {
                password.type = 'password';
                eyeIcon.classList.remove('text-indigo-600');
            }
        }

        function validateForm(event) {
            event.preventDefault();

            const username = document.getElementById('username');
            const password = document.getElementById('password');
            const usernameError = document.getElementById('usernameError');
            const passwordError = document.getElementById('passwordError');

            const btnLogin = document.getElementById('btn-login');
            const btnText = document.getElementById('btn-login-text');
            const btnSpinner = document.getElementById('btn-login-spinner');

            let valid = true;
            usernameError.classList.add('hidden');
            passwordError.classList.add('hidden');

            if (!username.value.trim()) {
                usernameError.classList.remove('hidden');
                valid = false;
            }

            if (!password.value.trim()) {
                passwordError.classList.remove('hidden');
                valid = false;
            }

            if (valid) {
                // Ubah tombol ke mode loading
                btnLogin.disabled = true;
                btnText.textContent = "Memproses...";
                btnSpinner.classList.remove("hidden");

                document.getElementById('loginForm').submit();
            }
        }
    </script>
</body>

</html>
