<x-app-layout>
    @php
        $me = Auth::user();

        $formatDuration = function ($task) {
            if ($task->started_at && $task->completed_at) {
                $minutes = $task->started_at->diffInMinutes($task->completed_at);
                if ($minutes >= 60) {
                    $hours = round($minutes / 60, 1);
                    return $hours . ' hr' . ($hours > 1 ? 's' : '');
                }
                return $minutes . ' min';
            }
            return $task->estimated_minutes ? $task->estimated_minutes . ' min' : '—';
        };
    @endphp

    <h1 class="vms-page-title mb-4">{{ __('Completed Tasks') }}</h1>

    <div class="vms-card has-table">
        <table class="vms-table mb-0">
            <thead>
                <tr>
                    <th>{{ __('Vehicle') }}</th>
                    <th>{{ __('Task Type') }}</th>
                    <th>{{ __('Completed By') }}</th>
                    <th>{{ __('Completed') }}</th>
                    <th>{{ __('Duration') }}</th>
                    <th class="text-right" style="width:1%; white-space:nowrap;">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $t)
                    @php $isMe = $t->mechanic_id === $me->id; @endphp
                    <tr>
                        <td class="font-weight-bold text-dark">{{ $t->vehicle?->shortId() }}</td>
                        <td>{{ $t->task_description }}</td>
                        <td>
                            @if ($isMe)
                                <span class="vms-pill-solid-blue">{{ __('You (Senior Mechanic)') }}</span>
                            @else
                                <span class="vms-pill-solid-green">{{ $t->mechanic?->name }}</span>
                            @endif
                        </td>
                        <td>{{ $t->completed_at?->format('Y-m-d') ?? $t->scheduled_at?->format('Y-m-d') }}</td>
                        <td>{{ $formatDuration($t) }}</td>
                        <td class="text-right" style="white-space:nowrap;">
                            <button type="button" class="vms-btn vms-btn-secondary vms-btn-sm"
                                    data-modal-url="{{ route('mechanic.tasks.reportModal', $t) }}">
                                <i class="fas fa-eye mr-1"></i> {{ __('View') }}
                            </button>
                            <a href="{{ route('reports.export') }}" class="vms-btn vms-btn-blue vms-btn-sm">
                                <i class="fas fa-download mr-1"></i> {{ __('Download') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">{{ __('No completed tasks yet.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
