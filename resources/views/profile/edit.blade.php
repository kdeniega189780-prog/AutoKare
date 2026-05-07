<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold text-white">{{ __('Profile') }}</h1>
    </x-slot>

    <div class="max-w-3xl space-y-4">
        <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm p-6">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm p-6">
            @include('profile.partials.update-password-form')
        </div>
        <div class="rounded-2xl border border-red-400/20 bg-red-500/10 backdrop-blur-sm p-6">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
