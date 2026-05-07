<x-app-layout>
    <h1 class="vms-page-title mb-4">{{ __('In Progress Tasks') }}</h1>

    @forelse ($tasks as $task)
        @php
            $priorityCls = match (strtolower($task->priority ?? 'medium')) {
                'high' => 'vms-pill-red-outline',
                'low' => 'vms-pill-green-outline',
                default => 'vms-pill-yellow-outline',
            };
            $estLabel = $task->estimated_minutes ? $task->estimated_minutes . ' minutes' : '—';
        @endphp
        <div class="vms-card vms-card-pad mb-3" style="border-color:#bfdbfe;">
            <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:12px;">
                <div class="flex-grow-1" style="min-width:0;">
                    <div class="d-flex align-items-center" style="gap:8px;">
                        <span class="font-weight-bold text-dark" style="font-size:15px;">{{ $task->vehicle?->shortId() }}</span>
                        <span class="vms-pill-blue-outline">{{ __('IN PROGRESS') }}</span>
                    </div>
                    <div class="text-muted mt-1" style="font-size:13px;">{{ $task->task_description }}</div>
                </div>
                <span class="{{ $priorityCls }}">{{ $task->priorityLabel() }}</span>
            </div>

            <div class="row mt-3 mb-3" style="margin:0 -6px;">
                <div class="col-md-4 px-2 mb-2">
                    <div class="vms-card vms-card-pad" style="background:#f9fafb; padding:10px 14px;">
                        <div class="vms-info-cell-label">{{ __('Started At') }}</div>
                        <div class="vms-info-cell-value">{{ $task->started_at?->format('g:i a') ?? '—' }}</div>
                    </div>
                </div>
                <div class="col-md-4 px-2 mb-2">
                    <div class="vms-card vms-card-pad" style="background:#f9fafb; padding:10px 14px;">
                        <div class="vms-info-cell-label">{{ __('Est. Duration') }}</div>
                        <div class="vms-info-cell-value">{{ $estLabel }}</div>
                    </div>
                </div>
                <div class="col-md-4 px-2 mb-2">
                    <div class="vms-card vms-card-pad" style="background:#f9fafb; padding:10px 14px;">
                        <div class="vms-info-cell-label">{{ __('Due Date') }}</div>
                        <div class="vms-info-cell-value">{{ $task->scheduled_at?->format('Y-m-d') ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="vms-actions">
                <button type="button" class="vms-btn vms-btn-green vms-btn-sm"
                        data-modal-url="{{ route('mechanic.tasks.completeModal', $task) }}">
                    <i class="fas fa-check-circle mr-1"></i> {{ __('Mark Complete') }}
                </button>
                <button type="button" class="vms-btn vms-btn-secondary vms-btn-sm"
                        data-modal-url="{{ route('mechanic.tasks.detailsModal', $task) }}">{{ __('View Details') }}</button>
                <button type="button" class="vms-btn vms-btn-secondary vms-btn-sm"
                        data-modal-url="{{ route('mechanic.tasks.noteModal', $task) }}">{{ __('Add Notes') }}</button>
            </div>
        </div>
    @empty
        <div class="vms-card vms-card-pad text-center text-muted">
            {{ __('No tasks in progress.') }}
        </div>
    @endforelse
</x-app-layout>
