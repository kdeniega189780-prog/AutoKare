<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="vms-modal-title">{{ __('Complete Task') }}</h2>
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
            <div class="vms-info-cell-label">{{ __('Started At:') }}</div>
            <div class="vms-info-cell-value">{{ $schedule->started_at?->format('g:i a') ?? '—' }}</div>
        </div>
        <div>
            <div class="vms-info-cell-label">{{ __('Priority:') }}</div>
            <div class="vms-info-cell-value">{{ $schedule->priorityLabel() }}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('mechanic.tasks.complete', $schedule) }}">
        @csrf
        <div class="mb-3">
            <label class="vms-label">{{ __('End Time') }} <span class="vms-required">*</span></label>
            <input type="text" name="end_time" class="vms-input" value="{{ now()->format('g:i a') }}" autocomplete="off" required>
        </div>

        <div class="mb-3">
            <label class="vms-label">{{ __('Work Completed Summary') }} <span class="vms-required">*</span></label>
            <textarea name="work_summary" rows="4" class="vms-textarea"
                      placeholder="{{ __('Describe the work performed (e.g., Changed oil and filter, rotated tires, etc.)...') }}" required></textarea>
        </div>

        <div class="mb-3">
            <label class="vms-label">{{ __('Parts Used') }}</label>
            <textarea name="parts_used" rows="3" class="vms-textarea"
                      placeholder="{{ __('List parts used with quantities (e.g., Oil Filter x1, Motor Oil 5W-30 x5 quarts)...') }}"></textarea>
        </div>

        <div class="mb-3">
            <label class="vms-label">{{ __('Labor Cost') }} <span class="vms-required">*</span></label>
            <input type="number" step="0.01" min="0" name="labor_cost" class="vms-input" placeholder="$0.00" required>
        </div>

        <div class="mb-3">
            <label class="vms-label">{{ __('Parts Cost') }}</label>
            <input type="number" step="0.01" min="0" name="parts_cost" class="vms-input" placeholder="$0.00">
        </div>

        <div class="mb-3">
            <label class="vms-label">{{ __('Recommendations for Customer') }}</label>
            <textarea name="recommendations" rows="3" class="vms-textarea"
                      placeholder="{{ __('Any recommendations or future maintenance needed (e.g., Brake pads at 60%, recommend check in 10k miles)...') }}"></textarea>
        </div>

        <div class="vms-banner-success-green mb-3">
            <strong>{{ __('Note:') }}</strong>
            {{ __('Completing this task will move it to your completed tasks and notify the customer.') }}
        </div>

        <div class="d-flex" style="gap:10px;">
            <button type="submit" class="vms-btn vms-btn-green flex-grow-1">
                <i class="fas fa-check-circle mr-1"></i> {{ __('Confirm & Mark Complete') }}
            </button>
            <button type="button" data-modal-close class="vms-btn vms-btn-secondary flex-grow-1">{{ __('Cancel') }}</button>
        </div>
    </form>
</div>
