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
        <title>{{ config('package.name', 'Laravel') }}</title>

        <meta property="og:title" content="{{ config('app.name') }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}/">
        <meta property="og:image" content="{{ package_asset('img/logo.png') }}" />
        <meta property="og:description" content="" />

        <!-- Canonical Link. -->
        <link rel="canonical" href="{{ url('/') }}/">

        <!-- Favicons -->
        @include(package_resource('partials.favicons'))

        <!-- Styles -->
        <link href="{{ package_asset('css/app.css') }}" rel="stylesheet">

        @datatablesCss()

        <!-- Styles -->
        @yield('css')
    </head>
    <body>     
        <div class="container mt-4 mb-5">
            <div class="row mt-3">
                <div class="col-lg-12 text-center">
                    <h3 class="font-weight-bold">Amprest Laravel Datatables</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    @include(config('package.name').'::layouts.alerts')
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-left my-3">
                    <h4 class="font-weight-bold">@yield('title')</h4>
                </div>
                <div class="col-lg-12">
                    @yield('content')
                </div>
            </div>
        </div>     
        <script src="{{ package_asset('js/manifest.js') }}"></script>
        <script src="{{ package_asset('js/vendor.js') }}"></script>
        <script src="{{ package_asset('js/app.js') }}"></script>
        <script src="{{ package_asset('js/master.js') }}"></script>

        @datatablesJs()

        @yield('js')
    </body>
</html>