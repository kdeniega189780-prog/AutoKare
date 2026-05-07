<x-app-layout>
    <div class="vms-page-header">
        <div>
            <h1 class="vms-page-title">My Vehicles</h1>
            <p class="vms-page-subtitle">Track maintenance and book service for your vehicles</p>
        </div>
        <button type="button" class="btn btn-dark"
                data-modal-url="{{ route('customer.vehicles.addModal') }}">
            <i class="fas fa-plus mr-1"></i> Add Vehicle
        </button>
    </div>

    @forelse ($vehicles as $v)
        <div class="vms-vehicle-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="vms-vehicle-card-title">{{ $v['title'] }}</div>
                    <div class="vms-vehicle-card-meta">License Plate: {{ $v['plate'] }}</div>
                </div>
                @if ($v['status'] === 'good')
                    <span class="badge badge-success p-2"><i class="fas fa-check mr-1"></i> Good</span>
                @else
                    <span class="badge badge-warning p-2"><i class="fas fa-exclamation-triangle mr-1"></i> Service Due</span>
                @endif
            </div>

            @if ($v['overdue'])
                <div class="alert alert-danger d-flex align-items-start">
                    <i class="fas fa-exclamation-circle mr-2 mt-1"></i>
                    <div>
                        <strong>Maintenance Overdue.</strong>
                        This vehicle was due for service on {{ $v['overdue_date'] }}.
                        Please schedule maintenance as soon as possible to avoid potential issues.
                    </div>
                </div>
            @endif

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="vms-service-block">
                        <div class="vms-service-block-label">Last Service</div>
                        <div class="vms-service-block-value">{{ $v['last_service'] ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-6 mt-2 mt-md-0">
                    <div class="vms-service-block">
                        <div class="vms-service-block-label">Next Service</div>
                        <div class="vms-service-block-value">{{ $v['next_service'] ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap" style="gap:8px;">
                @if ($v['overdue'])
                    <button type="button" class="btn btn-danger"
                            data-modal-url="{{ route('customer.vehicles.scheduleModal', $v['id']) }}">
                        <i class="fas fa-calendar-plus mr-1"></i> Schedule Service Now
                    </button>
                @else
                    <button type="button" class="btn btn-primary"
                            data-modal-url="{{ route('customer.vehicles.scheduleModal', $v['id']) }}">
                        <i class="fas fa-calendar-plus mr-1"></i> Schedule Service
                    </button>
                @endif
                <button type="button" class="btn btn-outline-secondary"
                        data-modal-url="{{ route('customer.vehicles.viewModal', $v['id']) }}">
                    <i class="fas fa-eye mr-1"></i> View Details
                </button>
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-car fa-2x mb-2 d-block"></i>
                No vehicles added yet.
            </div>
        </div>
    @endforelse
</x-app-layout>
