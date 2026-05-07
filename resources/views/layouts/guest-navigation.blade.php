@php
    $active = 'font-semibold text-slate-900 border-b-2 border-emerald-500 pb-0.5';
    $inactive = 'text-slate-600 hover:text-slate-900 border-b-2 border-transparent pb-0.5';
@endphp
<nav class="w-full max-w-md mx-auto mb-4 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-sm" aria-label="Guest navigation">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? $active : $inactive }}">{{ __('Home') }}</a>
    <a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? $active : $inactive }}">{{ __('About') }}</a>
    <a href="{{ route('login') }}" class="{{ request()->routeIs('login') ? $active : $inactive }}">{{ __('Log in') }}</a>
    @if (Route::has('register'))
        <a href="{{ route('register') }}" class="{{ request()->routeIs('register') ? $active : $inactive }}">{{ __('Register') }}</a>
    @endif
</nav>
