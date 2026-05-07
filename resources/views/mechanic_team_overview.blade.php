<x-app-layout>
    <h1 class="vms-page-title mb-4">{{ __('Team Overview') }}</h1>

    <div class="vms-card has-table mb-4">
        <div class="px-4 pt-3 pb-2" style="border-bottom:1px solid #f3f4f6;">
            <div class="font-weight-bold text-dark">{{ __('Team Members') }}</div>
        </div>
        <table class="vms-table mb-0">
            <thead>
                <tr>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Role') }}</th>
                    <th>{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($teamMembers as $m)
                    <tr>
                        <td class="font-weight-bold text-dark">{{ $m['name'] }}</td>
                        <td>{{ $m['role'] }}</td>
                        <td>
                            @if ($m['status'] === 'Working')
                                <span class="vms-pill-solid-green">{{ __('Working') }}</span>
                            @elseif ($m['status'] === 'On Break')
                                <span class="vms-pill-solid-yellow">{{ __('On Break') }}</span>
                            @else
                                <span class="vms-pill-gray">{{ __('Off Duty') }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="vms-card vms-card-pad">
        <div class="font-weight-bold text-dark">{{ __('Tasks Assigned to Team') }}</div>
        <div class="text-muted mb-3" style="font-size:12px;">
            {{ __("Track tasks you've delegated to team members (manual tracking)") }}
        </div>

        @forelse ($teamTasks as $task)
            @php
                $estLabel = $task->estimated_minutes
                    ? round($task->estimated_minutes / 60, 1) . ' hours'
                    : '—';
                $statusCls = match ($task->status) {
                    'completed' => 'vms-pill-solid-green',
                    'in_progress' => 'vms-pill-solid-yellow',
                    default => 'vms-pill-blue-outline',
                };
                $statusText = match ($task->status) {
                    'completed' => 'Completed',
                    'in_progress' => 'In Progress',
                    default => 'Assigned',
                };
                $assigneeName = $task->assignedMechanic?->name ?? $task->mechanic?->name ?? '—';
            @endphp
            <div class="vms-card vms-card-pad mb-3">
                <div class="d-flex justify-content-between align-items-start flex-wrap" style="gap:12px;">
                    <div class="flex-grow-1" style="min-width:0;">
                        <div class="font-weight-bold text-dark" style="font-size:15px;">{{ $task->vehicle?->shortId() }}</div>
                        <div class="text-muted" style="font-size:13px;">{{ $task->task_description }}</div>
                    </div>
                    <span class="{{ $statusCls }}">{{ $statusText }}</span>
                </div>

                <div class="row mt-3" style="margin:0 -6px;">
                    <div class="col-md-4 px-2 mb-2">
                        <div class="vms-card vms-card-pad" style="background:#f9fafb; padding:10px 14px;">
                            <div class="vms-info-cell-label">{{ __('Assigned To') }}</div>
                            <div class="vms-info-cell-value">{{ $assigneeName }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 px-2 mb-2">
                        <div class="vms-card vms-card-pad" style="background:#f9fafb; padding:10px 14px;">
                            <div class="vms-info-cell-label">{{ __('Assigned Date') }}</div>
                            <div class="vms-info-cell-value">{{ $task->scheduled_at?->format('Y-m-d') ?? '—' }}</div>
                        </div>
                    </div>
                    <div class="col-md-4 px-2 mb-2">
                        <div class="vms-card vms-card-pad" style="background:#f9fafb; padding:10px 14px;">
                            <div class="vms-info-cell-label">{{ __('Est. Duration') }}</div>
                            <div class="vms-info-cell-value">{{ $estLabel }}</div>
                        </div>
                    </div>
                </div>

                @if ($task->initial_notes || $task->description)
                    <div class="vms-notes-block">
                        <span class="lbl">{{ __('Notes:') }}</span>
                        <span>{{ $task->initial_notes ?: $task->description }}</span>
                    </div>
                @endif

                <div class="vms-actions mt-3">
                    @if ($task->status === 'pending')
                        <form method="POST" action="{{ route('mechanic.tasks.teamStatus', $task) }}">
                            @csrf
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="vms-btn vms-btn-orange vms-btn-sm">
                                <i class="fas fa-play mr-1"></i> {{ __('Mark as Started') }}
                            </button>
                        </form>
                    @elseif ($task->status === 'in_progress')
                        <form method="POST" action="{{ route('mechanic.tasks.teamStatus', $task) }}">
                            @csrf
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="vms-btn vms-btn-green vms-btn-sm">
                                <i class="fas fa-check-circle mr-1"></i> {{ __('Mark as Completed') }}
                            </button>
                        </form>
                    @endif
                    <button type="button" class="vms-btn vms-btn-secondary vms-btn-sm"
                            data-modal-url="{{ route('mechanic.tasks.detailsModal', $task) }}">{{ __('View Details') }}</button>
                </div>
            </div>
        @empty
            <div class="text-muted text-center py-3">{{ __("You haven't assigned any tasks to the team yet.") }}</div>
        @endforelse
    </div>
</x-app-layout>
