<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <h1 class="m-0">{{ __('My Vehicles') }}</h1>
            @can('create', App\Models\Vehicle::class)
                <a href="{{ route('vehicles.create') }}" class="btn btn-light border"
                   data-modal-url="{{ route('vehicles.createModal') }}" data-modal-title="{{ __('Add Vehicle') }}">
                    <i class="fas fa-plus mr-1"></i>{{ __('Add Vehicle') }}
                </a>
            @endcan
        </div>
    </x-slot>

    @forelse(($myVehicles ?? []) as $v)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <div class="font-weight-bold">{{ $v['title'] ?? '' }}</div>
                        <div class="text-muted small">{{ __('License Plate') }}: {{ $v['plate'] ?? '' }}</div>
                    </div>
                    @if (!empty($v['status']))
                        <span class="badge badge-light">{{ $v['status'] }}</span>
                    @endif
                </div>

                @if (!empty($v['alert']))
                    <div class="alert alert-danger mt-3 mb-0">
                        <div class="font-weight-bold">{{ $v['alert']['title'] ?? '' }}</div>
                        <div class="small">{{ $v['alert']['text'] ?? '' }}</div>
                    </div>
                @endif

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="border rounded p-2">
                            <div class="text-muted small">{{ __('Last Service') }}</div>
                            <div class="font-weight-bold">{{ $v['lastService'] ?? __('—') }}</div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-2 mt-md-0">
                        <div class="border rounded p-2">
                            <div class="text-muted small">{{ __('Next Service') }}</div>
                            <div class="font-weight-bold">{{ $v['nextService'] ?? __('—') }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ $v['scheduleUrl'] ?? route('schedules.index') }}" class="btn btn-dark btn-sm"
                       data-modal-url="{{ route('schedules.createModal', ['vehicle_id' => $v['vehicle_id'] ?? null]) }}" data-modal-title="{{ __('Book Appointment') }}">
                        {{ !empty($v['alert']) ? __('Schedule Service Now') : __('Schedule Service') }}
                    </a>
                    <a href="{{ $v['detailsUrl'] ?? route('vehicles.index') }}" class="btn btn-outline-secondary btn-sm"
                       @if(!empty($v['vehicle_id'])) data-modal-url="{{ route('vehicles.showModal', $v['vehicle_id']) }}" data-modal-title="{{ __('Vehicle Details') }}" @endif>
                        {{ __('View Details') }}
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="text-muted">{{ __('No vehicles yet.') }}</div>
    @endforelse
</x-app-layout>
