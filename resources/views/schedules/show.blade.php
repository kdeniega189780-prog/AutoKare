<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-xl font-semibold text-white">{{ __('Maintenance detail') }}</h1>
            <div class="flex flex-wrap items-center gap-3">
                @can('update', $schedule)
                    <a href="{{ route('schedules.edit', $schedule) }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 transition">
                        {{ __('Edit schedule') }}
                    </a>
                @endcan
                @can('fillServiceRecord', $schedule)
                    <a href="{{ route('service-records.edit', $schedule) }}" class="inline-flex items-center justify-center rounded-xl bg-white/10 px-4 py-2 text-sm font-semibold text-white hover:bg-white/15 border border-white/10 transition">
                        {{ __('Service record') }}
                    </a>
                @endcan
                @can('delete', $schedule)
                    <form method="POST" action="{{ route('schedules.destroy', $schedule) }}" class="inline" onsubmit="return confirm('{{ __('Delete this schedule?') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-500 transition">{{ __('Delete') }}</button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="mb-5 rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm p-6">
        <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2 text-sm">
            <div>
                <dt class="text-xs font-semibold uppercase tracking-widest text-slate-100/60">{{ __('Vehicle') }}</dt>
                <dd class="mt-1 text-white">
                    <a class="underline underline-offset-4 hover:text-white" href="{{ route('vehicles.show', $schedule->vehicle) }}">
                        {{ $schedule->vehicle->make }} {{ $schedule->vehicle->model }} ({{ $schedule->vehicle->license_plate }})
                    </a>
                </dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-widest text-slate-100/60">{{ __('Owner') }}</dt>
                <dd class="mt-1 text-white">{{ $schedule->vehicle->owner->name }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-widest text-slate-100/60">{{ __('Scheduled') }}</dt>
                <dd class="mt-1 text-white">{{ $schedule->scheduled_at->format('l, M j, Y g:i A') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-widest text-slate-100/60">{{ __('Mechanic') }}</dt>
                <dd class="mt-1 text-white">{{ $schedule->mechanic?->name ?? __('Unassigned') }}</dd>
            </div>
            <div>
                <dt class="text-xs font-semibold uppercase tracking-widest text-slate-100/60">{{ __('Status') }}</dt>
                <dd class="mt-2">
                    <span class="inline-flex rounded-lg border border-white/10 bg-white/5 px-2 py-0.5 text-xs text-slate-100/80 capitalize">
                        {{ str_replace('_', ' ', $schedule->status) }}
                    </span>
                </dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs font-semibold uppercase tracking-widest text-slate-100/60">{{ __('Task') }}</dt>
                <dd class="mt-1 text-white">{{ $schedule->task_description }}</dd>
            </div>
        </dl>
    </div>

    <div class="rounded-2xl border border-white/10 bg-white/5 backdrop-blur-sm overflow-hidden">
        <div class="px-4 py-3 bg-white/5 text-sm font-semibold text-white">{{ __('Service execution record') }}</div>
        <div class="p-6 text-sm text-slate-100/85">
            @if ($schedule->serviceRecord && ($schedule->serviceRecord->parts_used || $schedule->serviceRecord->labor_hours || $schedule->serviceRecord->notes || $schedule->serviceRecord->completed_at))
                <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-widest text-slate-100/60">{{ __('Parts used') }}</dt>
                        <dd class="mt-1 whitespace-pre-wrap text-white">{{ $schedule->serviceRecord->parts_used ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-widest text-slate-100/60">{{ __('Labor (hours)') }}</dt>
                        <dd class="mt-1 text-white">{{ $schedule->serviceRecord->labor_hours ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-widest text-slate-100/60">{{ __('Labor cost') }}</dt>
                        <dd class="mt-1 text-white"><x-money :value="$schedule->serviceRecord->labor_cost" /></dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-widest text-slate-100/60">{{ __('Parts cost') }}</dt>
                        <dd class="mt-1 text-white"><x-money :value="$schedule->serviceRecord->parts_cost" /></dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="text-xs font-semibold uppercase tracking-widest text-slate-100/60">{{ __('Notes') }}</dt>
                        <dd class="mt-1 whitespace-pre-wrap text-white">{{ $schedule->serviceRecord->notes ?: '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-semibold uppercase tracking-widest text-slate-100/60">{{ __('Completed at') }}</dt>
                        <dd class="mt-1 text-white">{{ $schedule->serviceRecord->completed_at?->format('M j, Y g:i A') ?? '—' }}</dd>
                    </div>
                </dl>
            @else
                <p class="text-slate-100/60 mb-0">{{ __('No service details recorded yet.') }}</p>
            @endif
        </div>
    </div>
</x-app-layout>
