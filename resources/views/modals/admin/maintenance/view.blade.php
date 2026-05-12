@php
    $r = $schedule->serviceRecord;
    $badge = match ($schedule->status) {
        'in_progress' => 'badge-warning',
        'completed' => 'badge-success',
        'cancelled' => 'badge-danger',
        default => 'badge-info',
    };
@endphp

<div class="modal-header">
    <h5 class="modal-title">Maintenance Record Details</h5>
    <button type="button" class="close" data-modal-close data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
</div>

<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-6">
            <label>Vehicle</label>
            <div class="form-control bg-light">{{ $schedule->vehicle?->shortId() }} — {{ $schedule->vehicle?->displayLabel() }}</div>
        </div>
        <div class="form-group col-md-6">
            <label>Service Type</label>
            <div class="form-control bg-light">{{ $schedule->task_description }}</div>
        </div>
        <div class="form-group col-md-4">
            <label>Date</label>
            <div class="form-control bg-light">{{ $schedule->scheduled_at?->format('Y-m-d') }}</div>
        </div>
        <div class="form-group col-md-4">
            <label>Status</label>
            <div class="form-control bg-light d-flex align-items-center">
                <span class="badge {{ $badge }} p-2">{{ $schedule->statusLabel() }}</span>
            </div>
        </div>
        <div class="form-group col-md-4">
            <label>Cost</label>
            <div class="form-control bg-light font-weight-bold">
                <x-money :value="$r?->totalCost() ?? 0" :decimals="2" />
            </div>
        </div>
    </div>

    <h6 class="font-weight-bold mt-3 mb-2"><i class="fas fa-clipboard mr-1"></i> Service Notes</h6>
    <div class="alert alert-secondary mb-0">
        {{ $r?->notes ?: 'No notes recorded.' }}
    </div>

    <h6 class="font-weight-bold mt-3 mb-2"><i class="fas fa-cogs mr-1"></i> Parts Used</h6>
    @if ($r && $r->parts->count())
        <ul class="list-group mb-0">
            @foreach ($r->parts as $line)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <span>{{ $line->part->name }}</span>
                    <span class="font-weight-bold"><x-money :value="$line->line_total" :decimals="2" /></span>
                </li>
            @endforeach
        </ul>
    @elseif ($r?->parts_used)
        <p class="text-muted mb-0">{{ $r->parts_used }}</p>
    @else
        <p class="text-muted mb-0">No parts recorded.</p>
    @endif
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-modal-close data-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary"
            data-modal-url="{{ route('admin.maintenance.editModal', $schedule) }}">
        <i class="fas fa-edit mr-1"></i> Edit Record
    </button>
</div>
