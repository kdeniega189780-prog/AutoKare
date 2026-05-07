@php
    $badge = match ($schedule->status) {
        'in_progress' => 'badge-warning',
        'completed' => 'badge-success',
        'cancelled' => 'badge-danger',
        default => 'badge-info',
    };
@endphp

<div class="modal-header">
    <h5 class="modal-title">Appointment Details</h5>
    <button type="button" class="close" data-modal-close aria-label="Close"><span>&times;</span></button>
</div>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            <label>Customer</label>
            <div class="form-control bg-light">
                {{ $schedule->vehicle?->owner?->name }}
                @if ($schedule->vehicle?->owner?->phone)
                    <small class="text-muted d-block">{{ $schedule->vehicle->owner->phone }}</small>
                @endif
            </div>
        </div>
        <div class="form-group col-md-6">
            <label>Vehicle</label>
            <div class="form-control bg-light">{{ $schedule->vehicle?->displayLabel() }}</div>
        </div>
        <div class="form-group col-md-6">
            <label>Service</label>
            <div class="form-control bg-light">{{ $schedule->task_description }}</div>
        </div>
        <div class="form-group col-md-6">
            <label>Status</label>
            <div><span class="badge {{ $badge }} p-2">{{ $schedule->statusLabel() }}</span></div>
        </div>
        <div class="form-group col-md-4">
            <label>Date</label>
            <div class="form-control bg-light">{{ $schedule->scheduled_at?->format('Y-m-d') }}</div>
        </div>
        <div class="form-group col-md-4">
            <label>Time</label>
            <div class="form-control bg-light">{{ $schedule->scheduled_at?->format('g:i A') }}</div>
        </div>
        <div class="form-group col-md-4">
            <label>Mechanic</label>
            <div class="form-control bg-light">{{ $schedule->mechanic?->name ?? '—' }}</div>
        </div>
        @if ($schedule->description)
            <div class="form-group col-md-12">
                <label>Notes</label>
                <div class="alert alert-secondary mb-0">{{ $schedule->description }}</div>
            </div>
        @endif
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-primary" data-modal-close>Close</button>
</div>
