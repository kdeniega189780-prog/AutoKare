@php
    $defaultMinutes = $schedule->estimated_minutes ?: 30;
    $durationOptions = [15, 30, 45, 60, 90, 120, 180, 240];
    if (! in_array($defaultMinutes, $durationOptions, true)) {
        $durationOptions[] = $defaultMinutes;
        sort($durationOptions);
    }
@endphp

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="vms-modal-title">{{ __('Start Task') }}</h2>
        <button type="button" class="vms-modal-close" data-modal-close aria-label="Close">&times;</button>
    </div>

    <div class="vms-info-grid">
        <div>
            <div class="vms-info-cell-label">{{ __('Vehicle:') }}</div>
            <div class="vms-info-cell-value">{{ $schedule->vehicle?->shortId() }}</div>
        </div>
        <div>
            <div class="vms-info-cell-label">{{ __('Service Type:') }}</div>
            <div class="vms-info-cell-value">{{ $schedule->task_description }}</div>
        </div>
        <div>
            <div class="vms-info-cell-label">{{ __('Priority:') }}</div>
            <div class="vms-info-cell-value">{{ $schedule->priorityLabel() }}</div>
        </div>
        <div>
            <div class="vms-info-cell-label">{{ __('Due Date:') }}</div>
            <div class="vms-info-cell-value">{{ $schedule->scheduled_at?->format('Y-m-d') }}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('mechanic.tasks.start', $schedule) }}">
        @csrf
        <div class="mb-3">
            <label class="vms-label">{{ __('Start Time') }}</label>
            <input type="text" name="start_time" class="vms-input" value="{{ now()->format('g:i a') }}" autocomplete="off">
        </div>

        <div class="mb-3">
            <label class="vms-label">{{ __('Initial Notes') }}</label>
            <textarea name="initial_notes" rows="3" class="vms-textarea"
                      placeholder="{{ __('Add any initial observations or notes...') }}">{{ old('initial_notes', $schedule->initial_notes) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="vms-label">{{ __('Estimated Duration') }}</label>
            <select name="estimated_minutes" class="vms-select">
                @foreach ($durationOptions as $opt)
                    <option value="{{ $opt }}" @selected($opt === $defaultMinutes)>
                        {{ $opt < 60 ? $opt . ' minutes' : ($opt % 60 === 0 ? ($opt/60) . ' hours' : round($opt/60, 1) . ' hours') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="vms-banner-info-blue mb-3">
            <strong>{{ __('Note:') }}</strong>
            {{ __('Starting this task will change its status to "In Progress" and log your start time.') }}
        </div>

        <div class="d-flex" style="gap:10px;">
            <button type="submit" class="vms-btn vms-btn-dark flex-grow-1">
                <i class="fas fa-check mr-1"></i> {{ __('Confirm & Start') }}
            </button>
            <button type="button" data-modal-close class="vms-btn vms-btn-secondary flex-grow-1">{{ __('Cancel') }}</button>
        </div>
    </form>
</div>
