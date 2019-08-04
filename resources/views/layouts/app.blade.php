<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta name="theme-color" content="#298fc7">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Title -->
        <title>{{ config('app.name', 'Laravel') }}</title>

        <meta property="og:title" content="{{ config('app.name') }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}/">
        <meta property="og:image" content="{{ package_asset('img/logo.png') }}" />
        <meta property="og:description" content="" />

        <!-- Canonical Link. -->
        <link rel="canonical" href="{{ url('/') }}/">

        <!-- Favicons -->
        @include('partials.favicons')

        <!-- Styles -->
        <link href="{{ package_asset('css/app.css') }}" rel="stylesheet">
        @yield('app.css')
    </head>
    <body>          
        @yield('content')
        <script src="{{ package_asset('js/manifest.js') }}"></script>
        <script src="{{ package_asset('js/vendor.js') }}"></script>
        <script src="{{ package_asset('js/app.js') }}"></script>
        <script src="{{ package_asset('js/master.js') }}"></script>
        @yield('js')
    </body>
</html>