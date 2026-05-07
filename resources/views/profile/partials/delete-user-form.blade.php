<section>
    <h2 class="text-lg font-semibold text-white">{{ __('Delete Account') }}</h2>
    <p class="mt-1 text-sm text-slate-100/70">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>

    <div class="mt-4">
        <button type="button" class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400/60 transition" data-toggle="modal" data-target="#confirmUserDeletion">
        {{ __('Delete Account') }}
    </button>
    </div>

    <div class="modal fade" id="confirmUserDeletion" tabindex="-1" role="dialog" aria-labelledby="confirmUserDeletionTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmUserDeletionTitle">{{ __('Confirm deletion') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="text-sm text-slate-600 mb-3">
                            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                        </p>
                        <x-input-label for="delete_password" :value="__('Password')" />
                        <x-text-input id="delete_password" name="password" type="password" class="mt-1" placeholder="{{ __('Password') }}" />
                        <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-200/30 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-500 transition">{{ __('Delete Account') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@push('scripts')
    @if ($errors->userDeletion->isNotEmpty())
        <script>
            $(function () {
                $('#confirmUserDeletion').modal('show');
            });
        </script>
    @endif
@endpush
