@php
    $last = $vehicle->maintenanceSchedules()->where('status', 'completed')->orderByDesc('scheduled_at')->first();
    $next = $vehicle->maintenanceSchedules()->where('status', 'pending')->orderBy('scheduled_at')->first();
@endphp

<div class="modal-header">
    <h5 class="modal-title">Vehicle Details</h5>
    <button type="button" class="close" data-modal-close aria-label="Close"><span>&times;</span></button>
</div>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            <label>Vehicle</label>
            <div class="form-control bg-light">{{ $vehicle->displayLabel() }}</div>
        </div>
        <div class="form-group col-md-6">
            <label>License Plate</label>
            <div class="form-control bg-light">{{ $vehicle->license_plate }}</div>
        </div>
        <div class="form-group col-md-6">
            <label>Mileage</label>
            <div class="form-control bg-light">{{ $vehicle->mileage !== null ? number_format($vehicle->mileage) . ' miles' : '—' }}</div>
        </div>
        @if ($vehicle->vin)
            <div class="form-group col-md-12">
                <label>VIN</label>
                <div class="form-control bg-light text-break">{{ $vehicle->vin }}</div>
            </div>
        @endif
        <div class="form-group col-md-12">
            <label>Last Service</label>
            <div class="form-control bg-light">
                {{ $last?->scheduled_at?->format('Y-m-d') ?? '—' }}
                <small class="text-muted">({{ $last?->task_description ?? '—' }})</small>
            </div>
        </div>
        <div class="form-group col-md-12">
            <label>Next Scheduled Service</label>
            <div class="form-control bg-light">
                {{ $next?->scheduled_at?->format('Y-m-d') ?? '—' }}
                <small class="text-muted">({{ $next?->task_description ?? '—' }})</small>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-modal-close>Close</button>
</div>
