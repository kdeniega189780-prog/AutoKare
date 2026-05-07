<x-app-layout>
    <h1 class="vms-page-title mb-4">{{ __('Customer Vehicles') }}</h1>

    <div class="vms-card has-table">
        <table class="vms-table mb-0">
            <thead>
                <tr>
                    <th>{{ __('Vehicle') }}</th>
                    <th>{{ __('Make/Model') }}</th>
                    <th>{{ __('License Plate') }}</th>
                    <th>{{ __('Mileage') }}</th>
                    <th>{{ __('VIN') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($vehicles as $v)
                    <tr>
                        <td class="font-weight-bold text-dark">{{ $v->shortId() }}</td>
                        <td>{{ $v->displayLabel() }}</td>
                        <td>{{ $v->license_plate }}</td>
                        <td>{{ $v->mileage !== null ? number_format($v->mileage) . ' mi' : '—' }}</td>
                        <td>{{ $v->vin ?? '—' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">{{ __('No customer vehicles in the system yet.') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-app-layout>
