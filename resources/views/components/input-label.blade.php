@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-slate-100/90']) }}>
    {{ $value ?? $slot }}
</label>
