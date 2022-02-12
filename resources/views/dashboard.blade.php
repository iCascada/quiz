@php
    $user = \Illuminate\Support\Facades\Auth::user();
    if (!$user->email_verified_at) {
        \Illuminate\Support\Facades\Session::flash('error', __('auth.account_not_verified'));
    }
@endphp
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet" href="/icons/font-awesome-5.8.2/css/all.min.css">
    <link rel="stylesheet" href="/icons/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/admin/adminlte.css">
    <link rel="stylesheet" href="/css/jquery-confirm.min.css">
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/mdb.min.css">
    <link rel="stylesheet" href="/css/app.css">
    @stack('css')
    <title>{{ $title }}</title>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper wrapper-dashboard">
    @include('private.components.navbar')
    @include('private.components.sidebar', $user)
    <div class="content-wrapper position-relative dashboard-content">
        <div class="alert-wrapper">
            @include('components.error')
            @include('components.success')
        </div>
        <div class="preloader">
            <div class="preloader-content">
                <div class="spinner-border"></div>
            </div>
        </div>
        @yield('content')
    </div>
    @include('private.components.footer')
</div>
</body>
</html>

