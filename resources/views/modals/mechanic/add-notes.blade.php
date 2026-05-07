<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="vms-modal-title">{{ __('Add Work Notes') }}</h2>
        <button type="button" class="vms-modal-close" data-modal-close aria-label="Close">&times;</button>
    </div>

    <div class="vms-card vms-card-pad mb-3" style="background:#f9fafb; padding:12px 14px;">
        <div class="vms-info-cell-label">{{ __('Task:') }}</div>
        <div class="vms-info-cell-value">{{ $schedule->vehicle?->shortId() }} - {{ $schedule->task_description }}</div>
    </div>

    <form method="POST" action="{{ route('mechanic.tasks.note', $schedule) }}">
        @csrf
        <div class="mb-3">
            <label class="vms-label">{{ __('Note') }}</label>
            <textarea name="progress_notes" rows="5" class="vms-textarea"
                      placeholder="{{ __('Add progress updates, observations, or issues encountered...') }}" required>{{ old('progress_notes', $schedule->progress_notes) }}</textarea>
        </div>

        <div class="vms-banner-info-blue mb-3">
            <strong>{{ __('Tip:') }}</strong>
            {{ __('Keep detailed notes during work for easier completion reporting.') }}
        </div>

        <div class="d-flex" style="gap:10px;">
            <button type="submit" class="vms-btn vms-btn-dark flex-grow-1">{{ __('Save Note') }}</button>
            <button type="button" data-modal-close class="vms-btn vms-btn-secondary flex-grow-1">{{ __('Cancel') }}</button>
        </div>
    </form>
</div>
