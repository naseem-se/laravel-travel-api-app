@extends('layouts.guest')

@section('title')
    Login
@endsection

@section('content')
    <div class="bg-gradient-to-br from-[#3B5998] to-[#2B4478] min-h-screen flex items-center justify-center p-5">
        <div class="w-full max-w-md">
            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-10">
                <!-- Logo -->
                <div class="flex justify-center mb-6">
                    <div class="w-16 h-16 bg-[#3B5998] rounded-lg flex items-center justify-center">
                        <span class="text-white text-2xl font-bold">E</span>
                    </div>
                </div>

                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-gray-900 text-2xl font-semibold mb-1">
                        EDM Solutions
                    </h1>
                    <p class="text-gray-500 text-sm">Super Admin Dashboard</p>
                </div>

                <form class="space-y-5" method="POST">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                        <input type="email" name="email" required autocomplete="email" placeholder="admin@edmsolutions.com"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3B5998] focus:border-transparent transition-all duration-200">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required autocomplete="current-password"
                                placeholder="Enter your password"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#3B5998] focus:border-transparent transition-all duration-200 pr-12">
                            <button type="button"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none"
                                onclick="togglePassword('password', this)">
                                <svg class="w-5 h-5 eye-open" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg class="w-5 h-5 eye-closed hidden" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember & Forgot Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 text-[#3B5998] border-gray-300 rounded focus:ring-2 focus:ring-[#3B5998]">
                            <span class="ml-2 text-gray-600">Remember me</span>
                        </label>
                        <a href="{{ route('pages.forget-password') }}"
                            class="text-[#3B5998] hover:text-[#2B4478] transition-colors">
                            Forgot password?
                        </a>
                    </div>

                    <!-- Login Button -->
                    <button type="button" onclick="window.location.href='{{ route('pages.dashboard') }}'"
                        class="w-full px-6 py-3.5 bg-[#3B5998] hover:bg-[#2B4478] rounded-lg text-white text-sm font-semibold transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                        </svg>
                        Sign In
                    </button>
                </form>

                <!-- Demo Credentials -->
                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <p class="text-center text-xs text-gray-600 mb-2 font-medium">Demo Credentials:</p>
                    <p class="text-center text-xs text-gray-500">Email: admin@edmsolutions.com</p>
                    <p class="text-center text-xs text-gray-500">Password: admin123</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const eyeOpen = button.querySelector('.eye-open');
            const eyeClosed = button.querySelector('.eye-closed');

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            }
        }
    </script>
@endsection
