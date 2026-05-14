<x-app-layout>

    <div class="vms-page-header">
        <div>
            <h1 class="vms-page-title">Vehicles</h1>
            <p class="vms-page-subtitle">Manage every vehicle in the workshop</p>
        </div>
        @can('create', App\Models\Vehicle::class)
            <button type="button" class="btn btn-dark"
                    data-modal-url="{{ route('vehicles.createModal') }}">
                <i class="fas fa-plus mr-1"></i> Add Vehicle for Customer
            </button>
        @endcan
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <form method="GET" action="{{ route('vehicles.index') }}" class="form-inline w-100">
                <div class="input-group w-100 vms-suggest-wrap">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="Search vehicles..." class="form-control"
                           data-suggest-url="{{ route('vehicles.suggest') }}" autocomplete="off">
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover vms-light mb-0">
                <thead>
                    <tr>
                        <th>Vehicle ID</th>
                        <th>Owner</th>
                        <th>Type</th>
                        <th>Year</th>
                        <th>Mileage</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($vehicles as $vehicle)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $vehicle->shortId() }}</td>
                            <td>{{ $vehicle->owner?->name ?? '—' }}</td>
                            <td>{{ $vehicle->type ?? '—' }}</td>
                            <td>{{ $vehicle->year }}</td>
                            <td>{{ $vehicle->mileage !== null ? number_format($vehicle->mileage) . ' mi' : '—' }}</td>
                            <td class="text-right">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                        data-modal-url="{{ route('vehicles.editModal', $vehicle) }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info"
                                        data-modal-url="{{ route('vehicles.showModal', $vehicle) }}">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No vehicles yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($vehicles->hasPages())
            <div class="card-footer clearfix">
                {{ $vehicles->appends(['q' => $q ?? null])->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
