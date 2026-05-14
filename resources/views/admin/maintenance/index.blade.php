<x-app-layout>
    <div class="vms-page-header">
        <div>
            <h1 class="vms-page-title">Maintenance Records</h1>
            <p class="vms-page-subtitle">View and edit maintenance history across all vehicles</p>
        </div>
        <span class="badge badge-info p-2"><i class="fas fa-info-circle mr-1"></i> View &amp; Edit Only - Records created by mechanics</span>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <form method="GET" action="{{ route('admin.maintenance.index') }}" class="form-inline w-100">
                <div class="form-row align-items-center w-100">
                    <div class="col-md-1 text-muted text-center">
                        <i class="fas fa-filter"></i>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                        <select name="vehicle_id" class="form-control" onchange="this.form.submit()">
                            <option value="">All Vehicles</option>
                            @foreach ($vehicles as $v)
                                <option value="{{ $v->id }}" @selected((string) $vehicleId === (string) $v->id)>{{ $v->shortId() }} — {{ $v->displayLabel() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select name="status" class="form-control" onchange="this.form.submit()">
                            <option value="">All Status</option>
                            @foreach (['pending' => 'Scheduled', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $val => $lbl)
                                <option value="{{ $val }}" @selected($status === $val)>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <input type="date" name="date" value="{{ $date }}" class="form-control" onchange="this.form.submit()">
                    </div>
                    <div class="col-md-1">
                        <a href="{{ route('admin.maintenance.index') }}" class="btn btn-default btn-block" title="Reset">
                            <i class="fas fa-undo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover vms-light mb-0">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Type</th>
                        <th>Date</th>
                        <th>Mechanic</th>
                        <th>Status</th>
                        <th>Cost</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $r)
                        @php
                            $badge = match ($r->status) {
                                'in_progress' => 'badge-warning',
                                'completed' => 'badge-success',
                                'cancelled' => 'badge-danger',
                                default => 'badge-info',
                            };
                        @endphp
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $r->vehicle?->shortId() }}</td>
                            <td>{{ $r->task_description }}</td>
                            <td>{{ $r->scheduled_at?->format('Y-m-d') }}</td>
                            <td>{{ $r->mechanic?->name ?? '—' }}</td>
                            <td><span class="badge {{ $badge }}">{{ $r->statusLabel() }}</span></td>
                            <td class="font-weight-bold"><x-money :value="$r->serviceRecord?->totalCost() ?? 0" :decimals="0" /></td>
                            <td class="text-right text-nowrap">
                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                        data-modal-url="{{ route('admin.maintenance.editModal', $r) }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-info"
                                        data-modal-url="{{ route('admin.maintenance.viewModal', $r) }}">
                                    <i class="fas fa-eye"></i> View
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No maintenance records found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($records->hasPages())
            <div class="card-footer">{{ $records->links() }}</div>
        @endif
    </div>
</x-app-layout>
