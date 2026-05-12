<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.adminlte.head')
<body class="hold-transition login-page" style="background: #f3f4f6;">
@include('layouts.partials.public-navbar')

<div class="login-box" style="width: 420px; margin-top: 4.5rem;">
    <div class="card shadow-sm" style="border-radius: 6px;">
        <div class="card-body p-4">
            <div class="text-center mb-4">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded border"
                     style="width: 56px; height: 56px; background: #111827; border-color: #111827;">
                    <i class="far fa-user text-white" style="font-size: 22px;"></i>
                </div>
                <div class="font-weight-bold" style="font-size: 15px;">{{ config('app.name') }}</div>
                <div class="text-muted" style="font-size: 12px;">{{ __('Sign in to continue') }}</div>
            </div>

            @if (session('status'))
                <div class="alert alert-info">{{ session('status') }}</div>
            @endif

            {{ $slot }}
        </div>
    </div>
</div>

@include('layouts.adminlte.scripts')
</body>
</html>
