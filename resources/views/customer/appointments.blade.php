<x-app-layout>
    <div class="vms-page-header">
        <div>
            <h1 class="vms-page-title">My Appointments</h1>
            <p class="vms-page-subtitle">Upcoming and in-progress service appointments</p>
        </div>
        <button type="button" class="btn btn-dark"
                data-modal-url="{{ route('customer.appointments.bookModal') }}">
            <i class="fas fa-plus mr-1"></i> Book New Appointment
        </button>
    </div>

    @forelse ($appointments as $appt)
        @php
            $badge = match ($appt->status) {
                'in_progress' => 'badge-warning',
                'completed' => 'badge-success',
                'cancelled' => 'badge-danger',
                default => 'badge-info',
            };
        @endphp
        <div class="vms-vehicle-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <div class="vms-vehicle-card-title">{{ $appt->vehicle?->displayLabel() }}</div>
                    <div class="vms-vehicle-card-meta">License Plate: {{ $appt->vehicle?->license_plate }}</div>
                </div>
                <span class="badge {{ $badge }} p-2">{{ $appt->statusLabel() }}</span>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="vms-service-block">
                        <div class="vms-service-block-label">Service</div>
                        <div class="vms-service-block-value">{{ $appt->task_description }}</div>
                    </div>
                </div>
                <div class="col-md-4 mt-2 mt-md-0">
                    <div class="vms-service-block">
                        <div class="vms-service-block-label">Date</div>
                        <div class="vms-service-block-value">{{ $appt->scheduled_at?->format('Y-m-d') }}</div>
                    </div>
                </div>
                <div class="col-md-4 mt-2 mt-md-0">
                    <div class="vms-service-block">
                        <div class="vms-service-block-label">Time</div>
                        <div class="vms-service-block-value">{{ $appt->scheduled_at?->format('g:i A') }}</div>
                    </div>
                </div>
            </div>

            @if ($appt->status === 'pending')
                <form method="POST" action="{{ route('customer.appointments.cancel', $appt) }}"
                      onsubmit="return confirm('Cancel this appointment?');" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger">
                        <i class="fas fa-times mr-1"></i> Cancel Appointment
                    </button>
                </form>
            @endif
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-calendar-alt fa-2x mb-2 d-block"></i>
                No upcoming appointments. Click "Book New Appointment" to schedule one.
            </div>
        </div>
    @endforelse

    @if ($errors->any())
        <script>
            (function () {
                // If booking validation fails, reopen the booking modal so the user can see/fix errors.
                var url = @json(route('customer.appointments.bookModal'));
                if (window.vmsOpenModal) {
                    window.vmsOpenModal(url, 'lg');
                } else if (window.jQuery) {
                    // Fallback: trigger the modal and load its content (layout scripts handle click too).
                    window.jQuery('#vms-modal').modal('show');
                    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }, credentials: 'same-origin' })
                        .then(function (r) { return r.text(); })
                        .then(function (html) {
                            var body = document.getElementById('vms-modal-body') || document.getElementById('vms-modal-content');
                            if (body) body.innerHTML = html;
                        });
                }
            })();
        </script>
    @endif
</x-app-layout>
