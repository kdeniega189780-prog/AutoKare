<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.adminlte.head')
<body class="d-flex flex-column min-vh-100">
@include('layouts.partials.public-navbar')

<main class="flex-grow-1" style="padding-top: 4.5rem;">
    @yield('content')
</main>

<footer class="py-4 bg-dark mt-auto">
    <div class="container text-center text-white-50 small">
        <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</footer>

<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="{{ $refAsset }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ $refAsset }}/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>
