<x-guest-layout>
    <p class="text-muted">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </p>

    <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">{{ __('Resend Verification Email') }}</button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-link px-0 mt-3 mt-sm-0">{{ __('Log Out') }}</button>
        </form>
    </div>
</x-guest-layout>
