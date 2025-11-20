{{-- @extends('layouts.guest')

@section('title')
    Welcome
@endsection

@section('content')
    <div class="w-full h-screen flex items-center justify-center bg-[#1F3C88] px-4">
        <div>
            <div class="mx-auto">
                <x-logo logo="{{ asset('images/white-logo.svg') }}" class="w-32 h-32 mx-auto" />
            </div>

            <div class="text-center justify-start mt-6">
                <span class="text-white sm:text-4xl text-2xl font-normal sm:leading-[60px] leading-[40px]">Welcome to
                    the<br /></span>
                <span class="text-white sm:text-4xl text-2xl font-semibold sm:leading-[60px] leading-[40px]">EDM Solution
                    Dashboard</span>
            </div>
            <div class="text-center mt-6 justify-start text-white text-2xl font-normal  leading-9">Right Staff Right
                Place Right Time</div>
            <div class="w-48 h-0.5 opacity-30 bg-white my-6 mx-auto"></div>
            <div class="flex items-center justify-center">
                <button onclick="window.location.href='{{ route('pages.login') }}'"
                    class="px-16 cursor-pointer sm:py-6 py-4 bg-white  rounded-[50px]  text-[#1F3C88] text-3xl font-semibold leading-9 gap-2">
                    Continued
                </button>
            </div>
        </div>
    </div>
@endsection --}}


@extends('layouts.guest')

@section('title')
    Welcome
@endsection

@section('content')
    <div
        class="w-full h-screen flex items-center justify-center bg-gradient-to-br from-[#1F3C88] via-[#2548A8] to-[#1F3C88] px-4 relative overflow-hidden">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute w-96 h-96 bg-white/5 rounded-full blur-3xl -top-20 -left-20 animate-pulse"></div>
            <div class="absolute w-96 h-96 bg-white/5 rounded-full blur-3xl -bottom-20 -right-20 animate-pulse delay-700">
            </div>
        </div>

        <!-- Main Content -->
        <div class="relative z-10 max-w-2xl w-full">
            <!-- Logo Section -->
            <div class="mx-auto mb-8 transform hover:scale-105 transition-transform duration-300">
                <x-logo logo="{{ asset('images/white-logo.svg') }}" class="w-32 h-32 mx-auto drop-shadow-2xl" />
            </div>

            <!-- Title Section -->
            <div class="text-center mb-8 space-y-2">
                <h1
                    class="text-white sm:text-5xl text-3xl font-light sm:leading-tight leading-tight tracking-wide animate-fade-in">
                    Welcome to the
                </h1>
                <h2
                    class="text-white sm:text-5xl text-3xl font-bold sm:leading-tight leading-tight tracking-wide animate-fade-in-delay">
                    EDM Solution Dashboard
                </h2>
            </div>

            <!-- Tagline -->
            <div class="text-center mb-10">
                <p class="text-white/90 sm:text-2xl text-xl font-light tracking-wide animate-fade-in-delay-2">
                    Right Staff Right Place Right Time
                </p>
            </div>

            <!-- Decorative Divider -->
            <div class="flex items-center justify-center mb-10">
                <div class="w-24 h-0.5 bg-white/30"></div>
                <div class="w-3 h-3 bg-white/40 rounded-full mx-4"></div>
                <div class="w-24 h-0.5 bg-white/30"></div>
            </div>

            <!-- CTA Button -->
            <div class="flex items-center justify-center animate-fade-in-delay-3">
                <button onclick="window.location.href='{{ route('pages.login') }}'"
                    class="group relative px-16 sm:py-6 py-4 bg-white rounded-full text-[#1F3C88] sm:text-3xl text-2xl font-semibold shadow-2xl hover:shadow-white/20 transform hover:scale-105 transition-all duration-300 overflow-hidden">
                    <span class="relative z-10">Continue</span>
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-white to-gray-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                </button>
            </div>

            <!-- Optional: Subtle Info Text -->
            <div class="text-center mt-12 opacity-0 hover:opacity-100 transition-opacity duration-500">
                <p class="text-white/60 text-sm">Click continue to access your dashboard</p>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.8s ease-out forwards;
        }

        .animate-fade-in-delay {
            opacity: 0;
            animation: fadeIn 0.8s ease-out 0.2s forwards;
        }

        .animate-fade-in-delay-2 {
            opacity: 0;
            animation: fadeIn 0.8s ease-out 0.4s forwards;
        }

        .animate-fade-in-delay-3 {
            opacity: 0;
            animation: fadeIn 0.8s ease-out 0.6s forwards;
        }

        .delay-700 {
            animation-delay: 700ms;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.3;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
@endsection
