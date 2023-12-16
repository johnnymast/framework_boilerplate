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
    Password reset email sent
@endsection

@section('content')
    <div class="mt-8">
        @include('layouts.parts.auth.notifications')
        <div class="text-white text-center">
            We have sent you a email with a link to reset your password. Follow this link to enter a new password.
        </div>
    </div>
@endsection
