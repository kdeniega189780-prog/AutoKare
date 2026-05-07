@props(['disabled' => false])

<input
    @disabled($disabled)
    {{ $attributes->merge(['class' => 'block w-full rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-sm text-white placeholder:text-slate-200/50 shadow-inner shadow-black/10 focus:border-emerald-400/60 focus:ring-2 focus:ring-emerald-400/30']) }}
>
