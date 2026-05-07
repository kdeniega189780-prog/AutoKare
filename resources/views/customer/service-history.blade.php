<x-app-layout>
    <div class="vms-page-header">
        <div>
            <h1 class="vms-page-title">Service History</h1>
            <p class="vms-page-subtitle">All completed services across your vehicles</p>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title font-weight-bold"><i class="fas fa-history mr-1"></i> Completed Services</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped vms-light mb-0">
                <thead>
                    <tr>
                        <th>Vehicle</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Mechanic</th>
                        <th class="text-right">Cost</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($records as $r)
                        <tr>
                            <td class="font-weight-bold text-dark">{{ $r->vehicle?->year }} {{ $r->vehicle?->make }} {{ $r->vehicle?->model }}</td>
                            <td>{{ $r->task_description }}</td>
                            <td>{{ $r->completed_at?->format('Y-m-d') ?? $r->scheduled_at?->format('Y-m-d') }}</td>
                            <td>{{ $r->mechanic?->name ?? '—' }}</td>
                            <td class="text-right font-weight-bold">${{ number_format($r->serviceRecord?->totalCost() ?? 0, 2) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No completed services yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
