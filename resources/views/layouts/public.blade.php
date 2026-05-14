<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.adminlte.head')
<body class="d-flex flex-column min-vh-100">
@include('layouts.partials.public-navbar')

<main class="flex-grow-1 vms-public-main">
    @yield('content')
</main>

<footer class="vms-public-footer py-4 mt-auto">
    <div class="container text-center">
        <div class="vms-public-footer-social mb-3">
            <a href="#" class="mx-2" aria-label="Facebook" title="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="mx-2" aria-label="Twitter" title="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" class="mx-2" aria-label="Instagram" title="Instagram"><i class="fab fa-instagram"></i></a>
        </div>
        <p class="small mb-2">
            <a href="#">{{ __('Privacy') }}</a>
            <span class="vms-public-footer-divider">|</span>
            <a href="#">{{ __('Terms') }}</a>
            <span class="vms-public-footer-divider">|</span>
            <a href="#">{{ __('Contact') }}</a>
        </p>
        <p class="mb-0 small text-white-50">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
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
