<div class="p-2">
    <div class="mb-3">
        <h4 class="mb-0">{{ __('Maintenance Record Details') }}</h4>
    </div>

    <div class="mb-3">
        <div class="row">
            <div class="col-md-6">
                <div class="text-muted small">{{ __('Vehicle') }}</div>
                <div class="font-weight-bold">{{ $schedule->vehicle->make }} {{ $schedule->vehicle->model }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">{{ __('Service Type') }}</div>
                <div class="font-weight-bold">{{ $schedule->task_description }}</div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="text-muted small">{{ __('Date') }}</div>
                <div class="font-weight-bold">{{ $schedule->scheduled_at->format('Y-m-d') }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">{{ __('Status') }}</div>
                <div class="font-weight-bold text-capitalize">{{ str_replace('_', ' ', $schedule->status) }}</div>
            </div>
        </div>

        @if ($schedule->serviceRecord)
            <hr>
            <div class="text-muted small mb-2">{{ __('Service Notes') }}</div>
            <div class="border rounded p-2 mb-3">{{ $schedule->serviceRecord->notes ?: '—' }}</div>

            <div class="row">
                <div class="col-md-6">
                    <div class="text-muted small">{{ __('Parts Used') }}</div>
                    <div class="border rounded p-2">{{ $schedule->serviceRecord->parts_used ?: '—' }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">{{ __('Total Cost') }}</div>
                    <div class="font-weight-bold">
                        <x-money :value="($schedule->serviceRecord->labor_cost ?? 0) + ($schedule->serviceRecord->parts_cost ?? 0)" />
                    </div>
                </div>
            </div>
        @endif
    </div>

    <div class="d-flex justify-content-between pt-2">
        <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-dark px-5">{{ __('Open Full Page') }}</a>
        <button class="btn btn-outline-secondary px-5" data-modal-close>{{ __('Close') }}</button>
    </div>
</div>
