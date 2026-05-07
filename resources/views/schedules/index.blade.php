<x-app-layout>
    <x-slot name="header">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
            <h1 class="m-0">{{ __('Maintenance scheduling') }}</h1>
            @can('create', App\Models\MaintenanceSchedule::class)
                <a href="{{ route('schedules.create') }}" class="btn btn-primary"
                   data-modal-url="{{ route('schedules.createModal') }}" data-modal-title="{{ __('Book Appointment') }}">
                    <i class="fas fa-plus mr-1"></i>{{ Auth::user()->isAdmin() ? __('Book Appointment') : __('Book Service') }}
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap mb-0 vms-table">
                <thead>
                    <tr>
                        <th>{{ __('Scheduled') }}</th>
                        <th>{{ __('Vehicle') }}</th>
                        <th>{{ __('Owner') }}</th>
                        <th>{{ __('Task') }}</th>
                        <th>{{ __('Mechanic') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th class="text-right">{{ __('') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $schedule)
                        <tr>
                            <td class="text-nowrap">{{ $schedule->scheduled_at->format('M j, Y g:i A') }}</td>
                            <td>{{ $schedule->vehicle->make }} {{ $schedule->vehicle->model }}</td>
                            <td>{{ $schedule->vehicle->owner->name }}</td>
                            <td title="{{ $schedule->task_description }}">{{ Str::limit($schedule->task_description, 60) }}</td>
                            <td>{{ $schedule->mechanic?->name ?? __('Unassigned') }}</td>
                            <td class="text-capitalize">
                                @php
                                    $map = [
                                        'pending' => ['Scheduled', 'vms-pill vms-pill-blue'],
                                        'in_progress' => ['In Progress', 'vms-pill vms-pill-yellow'],
                                        'completed' => ['Completed', 'vms-pill vms-pill-green'],
                                        'cancelled' => ['Cancelled', 'vms-pill vms-pill-gray'],
                                    ];
                                    [$label, $cls] = $map[$schedule->status] ?? [ucfirst($schedule->status), 'vms-pill vms-pill-gray'];
                                @endphp
                                <span class="{{ $cls }}">{{ __($label) }}</span>
                            </td>
                            <td class="text-right">
                                <a href="{{ route('schedules.show', $schedule) }}" class="btn btn-sm btn-outline-primary"
                                   data-modal-url="{{ route('schedules.showModal', $schedule) }}" data-modal-title="{{ __('Details') }}">{{ __('View') }}</a>
                                @can('update', $schedule)
                                    <a href="{{ route('schedules.edit', $schedule) }}" class="btn btn-sm btn-outline-secondary"
                                       data-modal-url="{{ route('schedules.editModal', $schedule) }}" data-modal-title="{{ __('Edit') }}">{{ __('Edit') }}</a>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">{{ __('No schedules found.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $schedules->links() }}
    </div>
</x-app-layout>
