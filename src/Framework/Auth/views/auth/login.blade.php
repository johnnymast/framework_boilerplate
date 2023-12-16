@extends('auth.layouts.default')

@section('content_left')
    <div>
        <h2 class="text-4xl font-bold text-white">{{ env('APP_NAME') }}</h2>

        <p class="max-w-xl mt-3 text-gray-300">{{ __('You need an account to use this website. Please login to your account or
            register a new one.') }}
        </p>
    </div>
@endsection

@section('title', 'Welcome to '.env('APP_NAME'))

@section('form_title')
    Sign in
@endsection

@section('form_subtitle')
    Sign in to access your account
@endsection

@section('content')
    <div class="mt-8">
        @include('layouts.parts.auth.notifications')
        <form method="POST" action="{{ route('auth.login') }}">
            {!! csrf() !!}
            <div>
                <label for="email"
                       class="block mb-2 text-sm text-gray-600 dark:text-gray-200 ">{{ __('Email Address') }}
                </label>
                <input
                        type="email"
                        name="email"
                        tabindex="1"
                        id="email"
                        value="{{ old('email')  }}"
                        placeholder="{{ __("example@example.com") }}"
                        autocomplete="username webauthn"
                        class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40"/>
            </div>
            <div class="mt-6">
                <div class="flex justify-between mb-2">
                    <label for="password"
                           class="text-sm text-gray-600 dark:text-gray-200">{{ __('Password') }}</label>
                </div>
                <input
                        type="password"
                        name="password"
                        id="password"
                        tabindex="2"
                        value="{{ old('password') }}"
                        placeholder="Your Password"
                        class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40"/>
            </div>
            <div class="mt-6">
                <input class="form-check-input" type="checkbox" name="remember"
                       id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label
                        class="form-check-label text-sm text-gray-600 dark:text-gray-200 justify-between mb-2"
                        for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
            <div class="mt-6">
                <button
                        class="w-full px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-blue-500 rounded-md hover:bg-blue-400 focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                    {{ __('Sign in') }}
                </button>
            </div>
        </form>
        <p class="mt-6 text-sm text-center text-gray-400">Don&#x27;t have an account yet?
            <a
                    href="{{ route('auth.register') }}"
                    class="text-blue-500 focus:outline-none focus:underline hover:underline">{{ __('Sign up') }}</a>.
        </p>
        <p class="mt-6 text-sm text-center text-gray-400">
            <a
                    href="{{ route('auth.password.request') }}"
                    class="text-blue-500 focus:outline-none focus:underline hover:underline">{{ __('Forgotten password?') }}</a>.
        </p>
    </div>
@endsection
