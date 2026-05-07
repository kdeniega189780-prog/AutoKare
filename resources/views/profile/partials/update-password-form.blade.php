<section>
    <h2 class="text-lg font-semibold text-white">{{ __('Update Password') }}</h2>
    <p class="mt-1 text-sm text-slate-100/70">{{ __('Ensure your account is using a long, random password to stay secure.') }}</p>

    <form method="post" action="{{ route('password.update') }}" class="mt-5 space-y-4">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" />
            <div class="mt-2">
                <x-text-input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" />
            <div class="mt-2">
                <x-text-input id="update_password_password" name="password" type="password" autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
            <div class="mt-2">
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        <div class="pt-1 flex flex-wrap items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            @if (session('status') === 'password-updated')
                <span class="text-sm text-emerald-200">{{ __('Saved.') }}</span>
            @endif
        </div>
    </form>
</section>
