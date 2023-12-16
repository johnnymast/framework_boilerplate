@extends('auth.layouts.default')

@section('content_left')
    <div>
        <h2 class="text-4xl font-bold text-white">{{env('APP_NAME')}}</h2>

        <p class="max-w-xl mt-3 text-gray-300">{{ __('Forgetting your password happens. Dont worry we will help you out.') }}</p>
    </div>
@endsection

@section('title', 'Welcome to '.env('APP_NAME'))

@section('form_title')
    Forgotten Password
@endsection

@section('form_subtitle')
    Request a password reset
@endsection

@section('content')
    <div class="mt-8">
        @include('layouts.parts.auth.notifications')
        <form method="POST" action="">
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
                        value="{{ old('email') }}"
                        placeholder="{{ __("example@example.com") }}"
                        class="block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40"/>
            </div>
            <div class="mt-6">
                <button
                        class="w-full px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-blue-500 rounded-md hover:bg-blue-400 focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
@endsection
