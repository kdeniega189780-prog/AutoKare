<x-guest-layout>
    <div class="alert alert-danger small py-2">403 — {{ __('Forbidden') }}</div>
    <h2 class="h5 font-weight-bold text-dark mt-2">{{ __('Access denied') }}</h2>
    <p class="small text-muted mt-2">
        @if (isset($exception) && $exception->getMessage() && $exception->getMessage() !== 'Forbidden')
            {{ $exception->getMessage() }}
        @else
            {{ __('This action is not allowed for your account role. Administrators, owners, and mechanics each have different permissions in this system.') }}
        @endif
    </p>
    <div class="mt-3 d-flex flex-wrap">
        <a href="{{ url()->previous() }}" class="btn btn-link btn-sm p-0 mr-3">{{ __('Go back') }}</a>
        @auth
            <a href="{{ route('dashboard') }}" class="btn btn-link btn-sm p-0">{{ __('Dashboard') }}</a>
        @else
            <a href="{{ route('home') }}" class="btn btn-link btn-sm p-0">{{ __('Home') }}</a>
        @endauth
    </div>
</x-guest-layout>
