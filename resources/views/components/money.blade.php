@props([
    'value' => null,
    'fallback' => '—',
    'decimals' => 2,
])

@php
    $v = $value;
    $isBlank = $v === null || $v === '';
@endphp

@if ($isBlank)
    <span {{ $attributes }}>{{ $fallback }}</span>
@else
    <span {{ $attributes }}>₱{{ number_format((float) $v, (int) $decimals) }}</span>
@endif

