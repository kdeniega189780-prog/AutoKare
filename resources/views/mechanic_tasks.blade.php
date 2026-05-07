<x-app-layout>
    <h1 class="vms-page-title mb-4">{{ __('Assigned Tasks') }}</h1>

    @forelse ($tasks as $task)
        @php
            $priorityCls = match (strtolower($task->priority ?? 'medium')) {
                'high' => 'vms-pill-red-outline',
                'low' => 'vms-pill-green-outline',
                default => 'vms-pill-yellow-outline',
            };
        @endphp
        <div class="vms-card vms-card-pad mb-3">
            <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:12px;">
                <div class="flex-grow-1" style="min-width:0;">
                    <div class="font-weight-bold text-dark" style="font-size:15px;">{{ $task->vehicle?->shortId() }}</div>
                    <div class="text-muted" style="font-size:13px;">{{ $task->task_description }}</div>
                    <div class="text-muted mt-2" style="font-size:12px;">
                        Due Date: <span class="text-dark font-weight-bold">{{ $task->scheduled_at?->format('Y-m-d') }}</span>
                        <span class="mx-3">Task ID: <span class="text-dark font-weight-bold">#{{ $task->id }}</span></span>
                    </div>
                </div>
                <span class="{{ $priorityCls }}">{{ $task->priorityLabel() }}</span>
            </div>
            <div class="vms-actions mt-3">
                <button type="button" class="vms-btn vms-btn-dark vms-btn-sm"
                        data-modal-url="{{ route('mechanic.tasks.startModal', $task) }}">{{ __('Start Task') }}</button>
                <button type="button" class="vms-btn vms-btn-blue vms-btn-sm"
                        data-modal-url="{{ route('mechanic.tasks.assignModal', $task) }}">{{ __('Assign to Team') }}</button>
                <button type="button" class="vms-btn vms-btn-secondary vms-btn-sm"
                        data-modal-url="{{ route('mechanic.tasks.detailsModal', $task) }}">{{ __('View Details') }}</button>
            </div>
        </div>
    @empty
        <div class="vms-card vms-card-pad text-center text-muted">
            <i class="fas fa-clipboard-check fa-2x mb-2 d-block text-success"></i>
            {{ __('No tasks assigned. Great job!') }}
        </div>
    @endforelse
</x-app-layout>
