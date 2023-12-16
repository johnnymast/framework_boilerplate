<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    @debug
    <script src="/debugbar/javascript"></script>
    <link rel="stylesheet" href="/debugbar/css"/>
    @enddebug
</head>
<body>

<div class="bg-gray-900">
    <div class="flex justify-center h-screen">
        <div class="bg-cover lg:block lg:w-2/3" style="background-image: url('/assets/img/auth.png')">
            <div class="flex items-center h-full px-20 bg-gray-900 bg-opacity-40">
                @yield('content_left')
            </div>
        </div>

        <div class="flex items-center w-full max-w-md px-6 mx-auto lg:w-2/6">
            <div class="flex-1">
                <div class="text-center">
                    <h2 class="text-4xl font-bold text-center text-gray-700 text-white">@yield('form_title')</h2>

                    <p class="mt-3 text-gray-500 text-gray-300">@yield('form_subtitle')</p>
                </div>
                <div class="mt-8">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</div>
@debug
{!! $phpdebugbar->render(); !!}
@enddebug
</body>
</html>
