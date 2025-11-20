{{-- @extends('layouts.guest')

@section('title')
    Register
@endsection

@section('content')
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .logo-icon {
            background-image: url("data:image/svg+xml,%3Csvg width='32' height='32' viewBox='0 0 32 32' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M8 8h6v6H8V8zm5 16H8v-6h5v6zm5-16h6v6h-6V8z' fill='white'/%3E%3Cpath d='M18 18h6v6h-6v-6z' fill='white'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
        }

        .google-btn {
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M19.99 10.187c0-.82-.069-1.417-.216-2.037H10.2v3.698h5.62c-.113.892-.725 2.166-1.725 3.04l-.015.102 2.818 2.264.194.02c1.796-1.687 2.918-4.178 2.918-7.087z' fill='%234285F4'/%3E%3Cpath d='M10.2 19.931c2.299 0 4.226-.75 5.635-2.044l-2.997-2.386c-.806.547-1.884.938-2.638.938-2.017 0-3.735-1.356-4.35-3.269l-.09.008-2.909 2.318-.038.095c1.411 2.84 4.312 4.34 7.387 4.34z' fill='%2334A853'/%3E%3Cpath d='M5.85 11.17c-.147-.452-.231-.938-.231-1.438s.084-.986.231-1.438l-.008-.109L2.069 5.765l-.08.037A9.861 9.861 0 0 0 .2 9.732a9.861 9.861 0 0 0 1.789 3.93l2.861-2.492z' fill='%23FBBC05'/%3E%3Cpath d='M10.2 3.853c1.434 0 2.405.619 2.96 1.133l2.186-2.153C13.883 1.433 12.298.731 10.2.731A9.312 9.312 0 0 0 2.813 5.071l2.89 2.486C6.32 5.646 8.075 3.853 10.2 3.853z' fill='%23EB4335'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 12px center;
        }

        .apple-btn {
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M14.55 0c.232 1.436-.6 2.855-1.32 3.75-.72.894-1.904 1.564-3.12 1.436-.232-1.378.673-2.855 1.335-3.692C12.13.73 13.372.058 14.55 0zm2.88 14.634c-.578 1.32-1.204 2.525-2.164 4.02-.845 1.32-1.843 2.97-3.206 2.99-1.305.02-1.69-.788-3.283-.788-1.593 0-2.048.768-3.293.807-1.305.04-2.456-1.787-3.302-3.107C.058 16.08-.578 12.637.462 10.15c.52-1.244 1.459-2.03 2.475-2.05 1.286-.02 2.495.884 3.283.884.788 0 2.264-1.092 3.84-.932.654.028 2.495.268 3.68 2.01-.096.058-2.206 1.311-2.187 3.908.02 3.071 2.64 4.106 2.677 4.125-.039.077-.404 1.378-1.3 2.74z' fill='%23000'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 12px center;
        }

        .eye-icon {
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg width='20' height='20' viewBox='0 0 20 20' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M10 3C5 3 1.73 7.11 1 10c.73 2.89 4 7 9 7s8.27-4.11 9-7c-.73-2.89-4-7-9-7zm0 12a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-8a3 3 0 1 0 0 6 3 3 0 0 0 0-6z' fill='%239CA3AF'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
    <div class="bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen flex items-center justify-center p-5">
        <div class=" w-full max-w-lg">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <x-logo logo="{{ asset('images/black-logo.svg') }}" class="w-32 h-32" />
            </div>

            <!-- Header -->
            <div class="text-center justify-start">
                <span class="text-[#1F3C88] sm:text-4xl text-2xl font-normal sm:leading-[60px] leading-[40px]">Welcome to
                    the<br /></span>
                <span class="text-[#1F3C88] sm:text-4xl text-2xl font-semibold sm:leading-[60px] leading-[40px]">EDM
                    Solution Dashboard</span>
            </div>

            <!-- Form -->
            <form class="space-y-4 max-w-xs container mx-auto">
                <!-- Full Name -->
                <div>
                    <label class="block text-[#1F3C88] text-sm mb-2 text-left">Full Name</label>
                    <input type="text"
                        class="w-full px-3.5 py-2.5 bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] outline outline-1 outline-offset-[-1px] outline-gray-300 inline-flex justify-start items-center gap-2">
                </div>

                <!-- Email Address -->
                <div>
                    <label class="block text-[#1F3C88] text-sm mb-2 text-left">Email Address</label>
                    <input type="email"
                        class="w-full px-3.5 py-2.5 bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] outline outline-1 outline-offset-[-1px] outline-gray-300 inline-flex justify-start items-center gap-2">
                </div>

                <!-- Phone Number -->
                <div>
                    <label class="block text-[#1F3C88] text-sm mb-2 text-left">Phone Number</label>
                    <input type="tel"
                        class="w-full px-3.5 py-2.5 bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] outline outline-1 outline-offset-[-1px] outline-gray-300 inline-flex justify-start items-center gap-2">
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-[#1F3C88] text-sm mb-2 text-left">Password</label>
                    <div class="relative">
                        <input type="password" id="password"
                            class="w-full px-3.5 py-2.5 bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] outline outline-1 outline-offset-[-1px] outline-gray-300 inline-flex justify-start items-center gap-2">
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 eye-icon"
                            onclick="togglePassword('password')"></div>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-[#1F3C88] text-sm mb-2 text-left">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="confirmPassword"
                            class="w-full px-3.5 py-2.5 bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] outline outline-1 outline-offset-[-1px] outline-gray-300 inline-flex justify-start items-center gap-2">
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2 w-5 h-5 eye-icon"
                            onclick="togglePassword('confirmPassword')"></div>
                    </div>
                </div>

                <!-- Sign Up Button -->
                <button onclick="{{ route('pages.login') }}" type="button"
                    class="cursor-pointer w-full mt-2 px-6 py-5 bg-blue-900 rounded-[100px]  text-center justify-center text-white text-sm font-normal leading-tight">
                    Sign up</button>
            </form>

            <!-- Divider -->
            <div class="flex items-center my-6">
                <div class="flex-1 h-px bg-gray-200"></div>
                <span class="px-4 text-gray-500 text-sm">or Continue with</span>
                <div class="flex-1 h-px bg-gray-200"></div>
            </div>

            <!-- Social Buttons -->
            <div class="container mx-auto max-w-xs">
                <div class="flex gap-4 mb-6">
                    <button
                        class="flex cursor-pointer w-full items-center justify-center px-7 space-x-3 py-3 rounded-[100px] outline outline-1 outline-offset-[-0.50px] outline-teal-600/80">
                        <x-logo logo="{{ asset('images/google-logo.svg') }}" class="w-5 h-5" />
                        <span>Google</span>
                    </button>
                    <button
                        class="flex cursor-pointer w-full items-center justify-center px-7 space-x-3 py-3 rounded-[100px] outline outline-1 outline-offset-[-0.50px] outline-teal-600/80">
                        <x-logo logo="{{ asset('images/apple-logo.svg') }}" class="w-5 h-5" />
                        <span>Apple</span>
                    </button>

                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center justify-start"><span class="text-zinc-500/80 text-sm font-normal  leading-tight">Have an
                    account?</span>
                <span class="text-zinc-500 text-sm font-normal  leading-tight">
                </span>
                <a href="{{ route('pages.login') }}" class="text-blue-900 text-sm font-normal  leading-tight">Log in</a>
            </div>
        </div>

        <script>
            function togglePassword(inputId) {
                const input = document.getElementById(inputId);
                if (input.type === 'password') {
                    input.type = 'text';
                } else {
                    input.type = 'password';
                }
            }
        </script>
    </div>
@endsection --}}


@extends('layouts.guest')

@section('title')
    Register
@endsection

@section('content')
    <div class="bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-screen flex items-center justify-center p-5">
        <div class="w-full max-w-md">
            <!-- Logo -->
            <div class="flex justify-center mb-6 animate-fade-in">
                <x-logo logo="{{ asset('images/black-logo.svg') }}" class="w-24 h-24 drop-shadow-lg" />
            </div>

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-[#1F3C88] text-3xl sm:text-4xl font-light mb-2">
                    Join the
                </h1>
                <h2 class="text-[#1F3C88] text-3xl sm:text-4xl font-bold">
                    EDM Solution Dashboard
                </h2>
                <p class="text-gray-600 mt-3 text-sm">Create your account to get started</p>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-2xl shadow-xl p-8 backdrop-blur-sm border border-gray-100">
                <form class="space-y-4" method="POST">
                    @csrf

                    <!-- Full Name -->
                    <div>
                        <label class="block text-[#1F3C88] text-sm font-medium mb-2">Full Name</label>
                        <input type="text" name="name" required autocomplete="name" placeholder="John Doe"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-300">
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label class="block text-[#1F3C88] text-sm font-medium mb-2">Email Address</label>
                        <input type="email" name="email" required autocomplete="email" placeholder="you@example.com"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-300">
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-[#1F3C88] text-sm font-medium mb-2">Phone Number</label>
                        <input type="tel" name="phone" required autocomplete="tel" placeholder="+92 300 1234567"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-300">
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-[#1F3C88] text-sm font-medium mb-2">Password</label>
                        <div class="relative">
                            <input type="password" id="password" name="password" required autocomplete="new-password"
                                placeholder="Create a strong password"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-300 pr-12">
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
                        <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-[#1F3C88] text-sm font-medium mb-2">Confirm Password</label>
                        <div class="relative">
                            <input type="password" id="confirmPassword" name="password_confirmation" required
                                autocomplete="new-password" placeholder="Confirm your password"
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 hover:border-gray-300 pr-12">
                            <button type="button"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none"
                                onclick="togglePassword('confirmPassword', this)">
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

                    <!-- Terms & Conditions -->
                    <div class="flex items-start">
                        <input type="checkbox" name="terms" required
                            class="w-4 h-4 mt-1 text-blue-900 border-gray-300 rounded focus:ring-2 focus:ring-blue-500">
                        <label class="ml-2 text-xs text-gray-600 leading-relaxed">
                            I agree to the <a href="#" class="text-blue-900 hover:text-blue-700 font-medium">Terms of
                                Service</a> and <a href="#"
                                class="text-blue-900 hover:text-blue-700 font-medium">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Sign Up Button -->
                    <button type="button" onclick="window.location.href='{{ route('pages.login') }}'"
                        class="w-full px-6 py-3.5 bg-gradient-to-r from-blue-900 to-blue-800 hover:from-blue-800 hover:to-blue-700 rounded-xl text-white text-sm font-semibold shadow-lg shadow-blue-900/30 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Create Account
                    </button>
                </form>

                <!-- Divider -->
                <div class="flex items-center my-6">
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                    <span class="px-4 text-gray-500 text-xs font-medium uppercase tracking-wider">Or sign up with</span>
                    <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                </div>

                <!-- Social Buttons -->
                <div class="grid grid-cols-2 gap-3">
                    <button type="button"
                        class="flex items-center justify-center gap-3 px-4 py-3 rounded-xl border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all duration-200 group">
                        <x-logo logo="{{ asset('images/google-logo.svg') }}" class="w-5 h-5" />
                        <span class="text-gray-700 text-sm font-medium group-hover:text-gray-900">Google</span>
                    </button>
                    <button type="button"
                        class="flex items-center justify-center gap-3 px-4 py-3 rounded-xl border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition-all duration-200 group">
                        <x-logo logo="{{ asset('images/apple-logo.svg') }}" class="w-5 h-5" />
                        <span class="text-gray-700 text-sm font-medium group-hover:text-gray-900">Apple</span>
                    </button>
                </div>
            </div>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <span class="text-gray-600 text-sm">Already have an account? </span>
                <a href="{{ route('pages.login') }}"
                    class="text-blue-900 text-sm font-semibold hover:text-blue-700 transition-colors">
                    Log in
                </a>
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.6s ease-out;
        }
    </style>

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

        // Optional: Password strength indicator
        const passwordInput = document.getElementById('password');
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const value = this.value;
                // Add your password strength validation logic here
            });
        }
    </script>
@endsection
