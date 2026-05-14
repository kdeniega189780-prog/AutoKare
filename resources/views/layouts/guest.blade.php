<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    @include('layouts.partials.vms-fonts')
    <link rel="stylesheet" href="{{ asset('vehicle-ref/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vehicle-ref/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vehicle-ref/dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vehicle-ref/dist/css/custom.css') }}">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <span class="vms-brand-icon-lg"><i class="fas fa-tools"></i></span>
        <div class="mt-2">{{ config('app.name') }}</div>
    </div>
    <div class="card card-outline card-primary">
        <div class="card-body login-card-body">
            {{ $slot }}
        </div>
    </div>
</div>

<script src="{{ asset('vehicle-ref/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vehicle-ref/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vehicle-ref/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
