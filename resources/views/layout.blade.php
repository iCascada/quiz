<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title }}</title>
        <link rel="stylesheet" href="/icons/font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/css/bootstrap.min.css">
        <link rel="stylesheet" href="/css/mdb.min.css">
        <link rel="stylesheet" href="/css/app.css">
        @stack('css')
    </head>
    <body>
    <div class="auth" id="app">
        @include('components.navbar')

        <div id="content">
            <div class="container-fluid">
                <div class="alert-wrapper">
                    @include('components.error')
                </div>
                <div class="alert-wrapper">
                    @include('components.success')
                </div>
                <div class="row">
                    @yield('content')
                </div>
            </div>
        </div>

        @include('components.footer')
    </div>
    </body>
</html>
