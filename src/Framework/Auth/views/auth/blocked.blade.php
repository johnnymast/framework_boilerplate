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
    You have been blocked
@endsection

@section('form_subtitle')
    Please with a short while before logging in again
@endsection

@section('content')
    <div class="mt-8">
        @include('layouts.parts.auth.notifications')
        <p class="text-white text-center">You have been blocked for a short while for failing to login for a few times. Please wait a litle while before trying again.</p>

        <div class="mt-6">
            <a href="{{ route('auth.login') }}"
               class="w-full block text-center px-4 py-2 tracking-wide text-white transition-colors duration-200 transform bg-blue-500 rounded-md hover:bg-blue-400 focus:outline-none focus:bg-blue-400 focus:ring focus:ring-blue-300 focus:ring-opacity-50">
                {{ __('Back to login') }}
            </a>
        </div>
    </div>
@endsection
