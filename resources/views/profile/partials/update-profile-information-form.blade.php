<section>
    <h2 class="text-lg font-semibold text-white">{{ __('Profile Information') }}</h2>
    <p class="mt-1 text-sm text-slate-100/70">{{ __("Update your account's profile information and email address.") }}</p>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-5 space-y-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <div class="mt-2">
                <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            </div>
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <div class="mt-2">
                <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2">
                    <p class="text-sm text-slate-100/70">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" type="submit" class="font-semibold text-slate-100/80 hover:text-white underline underline-offset-4">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="text-sm text-emerald-200">{{ __('A new verification link has been sent to your email address.') }}</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="pt-1 flex flex-wrap items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <span class="text-sm text-emerald-200">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
