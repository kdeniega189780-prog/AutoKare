<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Laravel'))</title>
    @include('layouts.partials.vms-fonts')
    <link rel="stylesheet" href="{{ $refAsset }}/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ $refAsset }}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="{{ $refAsset }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="{{ $refAsset }}/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="{{ $refAsset }}/dist/css/custom.css">
    <link rel="stylesheet" href="{{ $refAsset }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="{{ $refAsset }}/plugins/toastr/toastr.min.css">
    <script src="{{ $refAsset }}/plugins/jquery/jquery.min.js"></script>
    <script src="{{ $refAsset }}/plugins/jquery-ui/jquery-ui.min.js"></script>
    <script src="{{ $refAsset }}/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="{{ $refAsset }}/plugins/toastr/toastr.min.js"></script>
 @stack('styles')
</head>
