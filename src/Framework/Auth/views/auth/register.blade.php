@extends('auth.layouts.default')

@section('content_left')
    <div>
        <h2 class="text-4xl font-bold text-white">{{ env('APP_NAME') }}</h2>

        <p class="max-w-xl mt-3 text-gray-300">You need an account to use this website. You can register your account
            here.
        </p>
    </div>
@endsection

@section('title', 'Welcome to '.env('APP_NAME'))

@section('form_title')
    Register
@endsection

@section('form_subtitle')
    Sign up for a new account.
@endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @include('auth.layouts.parts.auth.notifications')
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('auth.register') }}">
                            {!! csrf() !!}
                            <div class="row mb-3">

                                <label for="name"
                                       class="block mb-2 text-sm text-gray-600 dark:text-gray-200 ">{{ __('Name') }}
                                </label>
                                <div class="col-md-6">
                                    <input id="name" type="text"
                                           class="form-control block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40"
                                           name="name"
                                           value="{{ old('name') }}"
                                           required
                                           placeholder="{{ __('Your name') }} "
                                           autocomplete="name"
                                           autofocus>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="email"
                                       class="block mb-2 text-sm text-gray-600 dark:text-gray-200 ">{{ __('Email Address') }}
                                </label>
                                <div class="col-md-6">
                                    <input
                                        id="email"
                                        type="email"
                                        class="form-control block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40"
                                        name="email"
                                        required
                                        placeholder="{{ __("example@example.com") }}"
                                        value="{{ old('email') }}"
                                        autocomplete="email">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password"
                                       class="block mb-2 text-sm text-gray-600 dark:text-gray-200">{{ __('Password') }}
                                </label>
                                <div class="col-md-6">
                                    <input id="password"
                                           type="password"
                                           class="form-control block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40"
                                           name="password"
                                           value="{{ old('password') }}"
                                           required
                                           placeholder="{{ __("Enter your password") }}"
                                           autocomplete="new-password">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="password-confirm"
                                       class="block mb-2 text-sm text-gray-600 dark:text-gray-200">{{ __('Confirm Password') }}</label>
                                <div class="col-md-6">
                                    <input id="password-confirm"
                                           type="password"
                                           class="form-control block w-full px-4 py-2 mt-2 text-gray-700 placeholder-gray-400 bg-white border border-gray-200 rounded-md dark:placeholder-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:border-gray-700 focus:border-blue-400 dark:focus:border-blue-400 focus:ring-blue-400 focus:outline-none focus:ring focus:ring-opacity-40"
                                           name="password_confirmation"
                                           placeholder="{{ __("Confirm your password") }}"
                                           required
                                           value="{{ old('password_confirmation') }}"
                                           autocomplete="new-password">
                                </div>
                            </div>
                            <div class="mt-6">
                                <button
                                    class="w-full px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-blue-500 rounded-md hover:bg-blue-400 focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
