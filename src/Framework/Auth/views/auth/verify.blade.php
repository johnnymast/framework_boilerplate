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
    Acccount created
@endsection

@section('form_subtitle')
    Please activate your account.
@endsection

@section('content')
    <div class="mt-8">
        @include('layouts.parts.auth.notifications')
        <div class="text-white text-center">
            We have sent you a email to confirm your email account. Please check your inbox and follow the link to
            activate you account.
        </div>
    </div>
@endsection
