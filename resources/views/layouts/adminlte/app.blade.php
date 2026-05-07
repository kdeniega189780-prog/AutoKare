<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.adminlte.head')
<body class="hold-transition layout-fixed text-sm vms-static">
<div class="wrapper">
    @include('layouts.adminlte.navbar')
    @include('layouts.adminlte.sidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-12">
                        <div class="m-0">
                            @isset($header)
                                {!! $header !!}
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                {{ $slot }}
            </div>
        </section>
    </div>

    @include('layouts.adminlte.footer')
</div>

<div class="modal fade" id="vms-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="vms-modal-title">{{ config('app.name') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="vms-modal-body">
                <div class="d-flex align-items-center justify-content-center py-5">
                    <div class="text-center text-muted">
                        <div class="spinner-border" role="status" aria-label="{{ __('Loading') }}"></div>
                        <div class="mt-3">{{ __('Loading…') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.adminlte.scripts')
</body>
</html>
