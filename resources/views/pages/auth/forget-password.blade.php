@extends('layouts.guest')

@section('title')
    Login
@endsection

@section('content')
    <div class="bg-gradient-to-br from-gray-50 to-gray-200 min-h-screen flex items-center justify-center p-5">
        <div class=" w-full max-w-lg">
            <!-- Logo -->
            <div class="flex justify-center mb-8">
                <x-logo logo="{{ asset('images/black-logo.svg') }}" class="w-32 h-32" />
            </div>

            <!-- Header -->
            <div
                class="text-center justify-start text-zinc-900 text-2xl font-medium capitalize leading-normal tracking-tight">
                Forgot password</div>
            <div
                class="text-center justify-start mt-4 text-zinc-500 text-base font-normal capitalize leading-normal tracking-wide">
                Enter your email Address to get the <br /> Password reset link</div>
            <!-- Form -->
            <form class="space-y-4 max-w-xs container mx-auto mt-8">

                <!-- Email Address -->
                <div>
                    <label class="block text-[#1F3C88] text-sm mb-2 text-left">Email Address</label>
                    <input type="email"
                        class="w-full px-3.5 py-2.5 bg-white rounded-lg shadow-[0px_1px_2px_0px_rgba(16,24,40,0.05)] outline outline-1 outline-offset-[-1px] outline-gray-300 inline-flex justify-start items-center gap-2">
                </div>

                <!-- Sign Up Button -->
                <button
                    class="cursor-pointer w-full mt-2 px-6 py-5 bg-blue-900 rounded-[100px]  text-center justify-center text-white text-sm font-normal leading-tight">
                    Password Reset</button>
            </form>

            <!-- Login Link -->
            <div class="text-center justify-center mt-6"><span
                    class="text-zinc-500/80 text-sm font-normal  leading-tight">Back to
                    login</span>
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
@endsection
