<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="description" content="description here">
    <meta name="keywords" content="keywords,here">

    <link rel="stylesheet" href="/css/app.css" data-n-g=""/>

    <script id="settings" type="application/json">
        { "page_title": "@yield('title')"}
    </script>
</head>
<body>

<div id="contents">
    @yield('content')
</div>

<script defer src="/assets/js/app.js"></script>

</body>
</html>
