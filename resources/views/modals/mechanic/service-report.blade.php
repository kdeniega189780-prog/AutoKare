@php
    $r = $schedule->serviceRecord;

    $duration = $schedule->started_at && $schedule->completed_at
        ? (function () use ($schedule) {
            $m = $schedule->started_at->diffInMinutes($schedule->completed_at);
            return $m >= 60 ? round($m/60, 1) . ' hr' : $m . ' min';
        })()
        : '—';

    $workLines = collect(preg_split('/\r?\n/', (string) ($r?->work_summary ?? '')))
        ->map(fn ($l) => trim(ltrim((string) $l, "-* ")))
        ->filter()
        ->values()
        ->all();

    $recLines = collect(preg_split('/\r?\n/', (string) ($r?->recommendations ?? '')))
        ->map(fn ($l) => trim(ltrim((string) $l, "-* ")))
        ->filter()
        ->values()
        ->all();
@endphp

<div class="p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="vms-modal-title">{{ __('Service Report') }}</h2>
        <button type="button" class="vms-modal-close" data-modal-close aria-label="Close">&times;</button>
    </div>

    <div class="vms-completion-banner mb-3">
        <div class="vms-completion-banner-title">
            <i class="fas fa-check-square"></i> {{ __('Task Completed') }}
        </div>
        <div class="vms-completion-banner-sub">
            {{ __('This service was completed successfully on') }} {{ $schedule->completed_at?->format('Y-m-d') ?? '—' }}
        </div>
    </div>

    <div class="vms-info-grid">
        <div>
            <div class="vms-info-cell-label">{{ __('Vehicle') }}</div>
            <div class="vms-info-cell-value">{{ $schedule->vehicle?->shortId() }}</div>
        </div>
        <div>
            <div class="vms-info-cell-label">{{ __('Service Type') }}</div>
            <div class="vms-info-cell-value">{{ $schedule->task_description }}</div>
        </div>
        <div>
            <div class="vms-info-cell-label">{{ __('Completed Date') }}</div>
            <div class="vms-info-cell-value">{{ $schedule->completed_at?->format('Y-m-d') ?? '—' }}</div>
        </div>
        <div>
            <div class="vms-info-cell-label">{{ __('Duration') }}</div>
            <div class="vms-info-cell-value">{{ $duration }}</div>
        </div>
    </div>

    <div class="vms-section-title">{{ __('Work Performed') }}</div>
    <div class="vms-bullet-card">
        @if (count($workLines))
            <ul class="vms-perf-list">
                @foreach ($workLines as $line)
                    <li>{{ $line }}</li>
                @endforeach
            </ul>
        @else
            <span class="text-muted">{{ __('No work summary recorded.') }}</span>
        @endif
    </div>

    <div class="vms-section-title">{{ __('Parts & Materials Used') }}</div>
    @php
        $partsTotal = 0.0;
        $hasLineItems = $r && $r->parts->count() > 0;
    @endphp
    @if ($hasLineItems)
        @foreach ($r->parts as $line)
            @php
                $name = $line->part->name;
                $qty = $line->quantity_used;
                $sub = trim(($line->part->oem_number ? 'Part #: ' . $line->part->oem_number : '')
                       . ($line->part->brand ? ($line->part->oem_number ? ' · ' : '') . 'Brand: ' . $line->part->brand : ''));
                $partsTotal += (float) $line->line_total;
            @endphp
            <div class="vms-row-line">
                <div>
                    <div class="row-name">{{ $name }}{{ $qty > 1 ? ' (x' . $qty . ')' : '' }}</div>
                    @if ($sub)
                        <div class="row-sub">{{ $sub }}</div>
                    @endif
                </div>
                <div class="row-amt"><x-money :value="$line->line_total" :decimals="2" /></div>
            </div>
        @endforeach
        <div class="vms-row-total">
            <span>{{ __('Total Parts:') }}</span>
            <span><x-money :value="$partsTotal" :decimals="2" /></span>
        </div>
    @else
        @php
            $parts = $schedule->requiredPartsList();
            $partsCost = (float) ($r?->parts_cost ?? 0);
            $unit = (count($parts) > 0 && $partsCost > 0) ? $partsCost / count($parts) : 0.0;
        @endphp
        @if (count($parts))
            @foreach ($parts as $p)
                <div class="vms-row-line">
                    <div>
                        <div class="row-name">{{ $p['name'] }}</div>
                        <div class="row-sub">{{ __('Qty:') }} {{ $p['qty'] }}</div>
                    </div>
                    <div class="row-amt"><x-money :value="$unit" :decimals="2" /></div>
                </div>
            @endforeach
            <div class="vms-row-total">
                <span>{{ __('Total Parts:') }}</span>
                <span><x-money :value="$partsCost" :decimals="2" /></span>
            </div>
        @else
            <div class="text-muted small">{{ $r?->parts_used ?: __('No parts recorded.') }}</div>
        @endif
    @endif

    <div class="vms-section-title">{{ __('Labor') }}</div>
    <div class="vms-row-line">
        <div class="row-name">{{ __('Standard') }} {{ $schedule->task_description }} {{ __('Service') }}</div>
        <div class="row-amt"><x-money :value="$r?->labor_cost ?? 0" :decimals="2" /></div>
    </div>

    @if (count($recLines))
        <div class="vms-section-title">{{ __('Recommendations') }}</div>
        <div class="vms-recommendation-card">
            <ul>
                @foreach ($recLines as $line)
                    <li>{{ $line }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="vms-section-title">{{ __('Total Cost') }}</div>
    <div class="vms-total-bar">
        <span class="vms-total-bar-label">{{ __('Total:') }}</span>
        <span class="vms-total-bar-value"><x-money :value="$r?->totalCost() ?? 0" :decimals="2" /></span>
    </div>

    @if ($r?->notes)
        <div class="vms-section-title">{{ __('Mechanic Notes') }}</div>
        <div class="vms-card vms-card-pad vms-surface-subtle" style="font-size:13px; color:#374151; white-space:pre-wrap;">{{ $r->notes }}</div>
    @endif

    <div class="d-flex mt-4" style="gap:10px;">
        <a href="{{ route('reports.export') }}" class="vms-btn vms-btn-dark flex-grow-1">
            <i class="fas fa-download mr-1"></i> {{ __('Download Report') }}
        </a>
        <button type="button" data-modal-close class="vms-btn vms-btn-secondary flex-grow-1">{{ __('Close') }}</button>
    </div>
</div>
