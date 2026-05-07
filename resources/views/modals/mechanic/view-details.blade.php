@php
    $priorityCls = match (strtolower($schedule->priority ?? 'medium')) {
        'high' => 'vms-pill-red-outline',
        'low' => 'vms-pill-green-outline',
        default => 'vms-pill-yellow-outline',
    };
    $statusText = match ($schedule->status) {
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
        default => 'Assigned',
    };
@endphp

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="vms-modal-title">{{ __('Task Details') }}</h2>
        <button type="button" class="vms-modal-close" data-modal-close aria-label="Close">&times;</button>
    </div>

    <div class="vms-info-grid vms-info-grid-3">
        <div>
            <div class="vms-info-cell-label">{{ __('Vehicle') }}</div>
            <div class="vms-info-cell-value">{{ $schedule->vehicle?->shortId() }}</div>
        </div>
        <div>
            <div class="vms-info-cell-label">{{ __('Service Type') }}</div>
            <div class="vms-info-cell-value">{{ $schedule->task_description }}</div>
        </div>
        <div>
            <div class="vms-info-cell-label">{{ __('Priority') }}</div>
            <div><span class="{{ $priorityCls }}">{{ $schedule->priorityLabel() }}</span></div>
        </div>
        <div>
            <div class="vms-info-cell-label">{{ __('Due Date') }}</div>
            <div class="vms-info-cell-value">{{ $schedule->scheduled_at?->format('Y-m-d') }}</div>
        </div>
        <div>
            <div class="vms-info-cell-label">{{ __('Task ID') }}</div>
            <div class="vms-info-cell-value">#{{ $schedule->id }}</div>
        </div>
        <div>
            <div class="vms-info-cell-label">{{ __('Status') }}</div>
            <div class="vms-info-cell-value">{{ $statusText }}</div>
        </div>
    </div>

    <div class="vms-section-title">{{ __('Vehicle Information') }}</div>
    <div class="vms-card vms-card-pad" style="background:#f9fafb;">
        <div class="vms-info-row">
            <span class="lbl">{{ __('Make/Model:') }}</span>
            <span class="val">{{ $schedule->vehicle?->displayLabel() }}</span>
        </div>
        <div class="vms-info-row">
            <span class="lbl">{{ __('License Plate:') }}</span>
            <span class="val">{{ $schedule->vehicle?->license_plate ?? '—' }}</span>
        </div>
        <div class="vms-info-row">
            <span class="lbl">{{ __('Current Mileage:') }}</span>
            <span class="val">{{ $schedule->vehicle?->mileage !== null ? number_format($schedule->vehicle->mileage) . ' mi' : '—' }}</span>
        </div>
        <div class="vms-info-row">
            <span class="lbl">{{ __('VIN:') }}</span>
            <span class="val">{{ $schedule->vehicle?->vin ?? '—' }}</span>
        </div>
    </div>

    <div class="vms-section-title">{{ __('Service Instructions') }}</div>
    <div class="vms-card vms-card-pad" style="background:#f9fafb; font-size:13px; color:#374151;">
        {{ $schedule->service_instructions ?: $schedule->description ?: $schedule->task_description }}
    </div>

    <div class="vms-section-title">{{ __('Required Parts') }}</div>
    @php $parts = $schedule->requiredPartsList(); @endphp
    @if (count($parts))
        @foreach ($parts as $p)
            <div class="vms-row-line">
                <div class="row-name">{{ $p['name'] }}</div>
                <div class="row-amt">{{ __('Qty:') }} {{ $p['qty'] }}</div>
            </div>
        @endforeach
    @else
        <div class="text-muted small">{{ __('No parts required.') }}</div>
    @endif

    <div class="vms-section-title">{{ __('Customer Contact') }}</div>
    <div class="vms-card vms-card-pad" style="background:#f9fafb; font-size:13px;">
        <div><strong>{{ __('Name:') }}</strong> {{ $schedule->vehicle?->owner?->name ?? '—' }}</div>
        <div><strong>{{ __('Phone:') }}</strong> {{ $schedule->vehicle?->owner?->phone ?? '—' }}</div>
        <div><strong>{{ __('Email:') }}</strong> {{ $schedule->vehicle?->owner?->email ?? '—' }}</div>
    </div>

    <div class="d-flex mt-4" style="gap:10px;">
        <form method="POST" action="{{ route('mechanic.tasks.start', $schedule) }}" class="flex-grow-1">
            @csrf
            <button type="submit" class="vms-btn vms-btn-dark vms-btn-block">
                <i class="fas fa-play mr-1"></i> {{ __('Start Task') }}
            </button>
        </form>
        <button type="button" data-modal-close class="vms-btn vms-btn-secondary flex-grow-1">{{ __('Close') }}</button>
    </div>
</div>
