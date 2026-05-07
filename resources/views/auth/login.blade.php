<x-guest-layout>
    <p class="login-box-msg">Sign in to your account</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="input-group mb-3">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                   class="form-control @error('email') is-invalid @enderror" placeholder="Email address">
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-envelope"></span></div>
            </div>
            @error('email')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input id="password" type="password" name="password" required autocomplete="current-password"
                   class="form-control @error('password') is-invalid @enderror" placeholder="Password">
            <div class="input-group-append">
                <div class="input-group-text"><span class="fas fa-lock"></span></div>
            </div>
            @error('password')
                <span class="invalid-feedback d-block">{{ $message }}</span>
            @enderror
        </div>

        <div class="row align-items-center">
            <div class="col-7">
                <div class="icheck-primary">
                    <input type="checkbox" id="remember_me" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember_me">Remember me</label>
                </div>
            </div>
            <div class="col-5">
                <button type="submit" class="btn btn-dark btn-block">
                    <i class="fas fa-sign-in-alt mr-1"></i> Sign In
                </button>
            </div>
        </div>
    </form>

    @if (Route::has('register'))
        <p class="text-center mt-3 mb-0">
            <a href="{{ route('register') }}" class="text-muted small">
                {{ __("Don't have an account?") }} <strong>{{ __('Register a new membership') }}</strong>
            </a>
        </p>
    @endif

    <hr class="my-4">

    <p class="text-center text-muted small mb-2 font-weight-bold">Demo: Select a role to preview</p>
    <div class="vms-demo-roles">
        @foreach ([
            ['label' => 'Admin', 'email' => 'admin@example.com', 'icon' => 'fa-user-shield', 'color' => 'danger'],
            ['label' => 'Senior Mechanic', 'email' => 'mechanic@example.com', 'icon' => 'fa-wrench', 'color' => 'info'],
            ['label' => 'Customer', 'email' => 'customer@example.com', 'icon' => 'fa-user', 'color' => 'purple'],
        ] as $demo)
            <button type="button"
                    onclick="document.getElementById('email').value='{{ $demo['email'] }}'; document.getElementById('password').value='password';"
                    class="vms-demo-role-btn">
                <span class="vms-demo-role-icon text-{{ $demo['color'] }}"><i class="fas {{ $demo['icon'] }}"></i></span>
                <span class="vms-demo-role-text">
                    <span class="vms-demo-role-name">{{ $demo['label'] }}</span>
                    <span class="vms-demo-role-email">{{ $demo['email'] }}</span>
                </span>
            </button>
        @endforeach
    </div>
    <p class="text-center text-muted small mt-3 mb-0">Password for all demo accounts: <code>password</code></p>
</x-guest-layout>
