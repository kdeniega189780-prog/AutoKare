<x-guest-layout>
    @if (session('status'))
        <div class="alert alert-info">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="input-group mb-3">
            <select id="role" name="role" required class="form-control @error('role') is-invalid @enderror">
                <option value="owner" @selected(old('role', 'owner') === 'owner')>{{ __('Customer') }}</option>
                <option value="mechanic" @selected(old('role') === 'mechanic')>{{ __('Mechanic') }}</option>
            </select>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user-tag"></span>
                </div>
            </div>
            @error('role')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="form-control @error('name') is-invalid @enderror" placeholder="{{ __('Full name') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
            @error('name')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="form-control @error('email') is-invalid @enderror" placeholder="{{ __('Email') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="input-group mb-3">
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="{{ __('Confirm password') }}">
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
            @error('password_confirmation')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Register') }}</button>
            </div>
        </div>
    </form>

    <p class="mb-0 mt-3">
        <a href="{{ route('login') }}">{{ __('I already have a membership') }}</a>
    </p>
</x-guest-layout>
