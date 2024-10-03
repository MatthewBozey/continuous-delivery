<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('images/favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon/favicon-16x16.png') }}">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{ asset('images/favicon/ms-icon-144x144.png') }}">
    <meta name="theme-color" content="#ffffff">

    <title>{{ config('app.name', 'Laravel') }}</title>



    <script src="{{ asset('js/jquery.js') }}"></script>
</head>

<body class="@yield('body_class')">
    <div class="row flex-nowrap m-0" id="app">

        <div class="container-fluid col pt-md-10px mx-md-10px px-0 container-main">
            <notifications position="top"></notifications>
            <cat-loader></cat-loader>
            @yield('content')
        </div>
    </div>
    @yield('custom_js')
</body>

</html>
