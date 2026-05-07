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

    @if (session('approval_pending'))
        <div class="modal fade" id="approvalPendingModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Awaiting Admin Approval') }}</h5>
                    </div>
                    <div class="modal-body">
                        <p class="mb-0">
                            {{ __('Registration submitted. Your account is awaiting admin approval.') }}
                        </p>
                    </div>
                    <div class="modal-footer">
                        <a class="btn btn-primary" href="{{ route('login') }}">{{ __('Go to Login') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <script>
            (function () {
                if (window.jQuery && window.jQuery.fn && window.jQuery.fn.modal) {
                    window.jQuery('#approvalPendingModal').modal({ backdrop: 'static', keyboard: false });
                    window.jQuery('#approvalPendingModal').modal('show');
                }
            })();
        </script>
    @endif
</x-guest-layout>
