<x-app-layout>
    <div class="vms-page-header">
        <div>
            <h1 class="vms-page-title">Appointments</h1>
            <p class="vms-page-subtitle">Book, view and edit customer appointments</p>
        </div>
        <button type="button" class="btn btn-dark"
                data-modal-url="{{ route('admin.appointments.createModal') }}">
            <i class="fas fa-plus mr-1"></i> Book Appointment
        </button>
    </div>

    <div class="vms-banner vms-banner-purple">
        <div class="d-flex align-items-start">
            <i class="fas fa-info-circle mr-3 mt-1 text-purple"></i>
            <div>
                <h5>Hybrid Service Model</h5>
                <p>
                    <strong>Two ways to book:</strong>
                    Admin can book appointments for walk-in/phone customers (you're doing this now),
                    OR customers with online accounts can book themselves 24/7 through their portal.
                    <strong>&rarr; Reduces phone calls and improves efficiency!</strong>
                </p>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.appointments.index') }}" class="form-inline w-100">
                <div class="input-group w-100 vms-suggest-wrap">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                    </div>
                    <input type="text" name="q" value="{{ $q }}" placeholder="Search appointments by customer, vehicle or service..." class="form-control"
                           data-suggest-url="{{ route('admin.appointments.suggest') }}" autocomplete="off">
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover vms-light mb-0">
                <thead>
                    <tr>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($schedules as $s)
                        @php
                            $badge = match ($s->status) {
                                'in_progress' => 'badge-warning',
                                'completed' => 'badge-success',
                                'cancelled' => 'badge-danger',
                                default => 'badge-info',
                            };
                        @endphp
                        <tr>
                            <td>
                                <div class="font-weight-bold text-dark">{{ $s->vehicle?->owner?->name }}</div>
                                <small class="text-muted">{{ $s->vehicle?->owner?->phone }}</small>
                            </td>
                            <td>{{ $s->vehicle?->displayLabel() }}</td>
                            <td>{{ $s->task_description }}</td>
                            <td>{{ $s->scheduled_at?->format('Y-m-d') }}</td>
                            <td>{{ $s->scheduled_at?->format('g:i A') }}</td>
                            <td><span class="badge {{ $badge }}">{{ $s->statusLabel() }}</span></td>
                            <td class="text-right text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                        data-modal-url="{{ route('admin.appointments.editModal', $s) }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info"
                                        data-modal-url="{{ route('admin.appointments.viewModal', $s) }}">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No appointments found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($schedules->hasPages())
            <div class="card-footer">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
