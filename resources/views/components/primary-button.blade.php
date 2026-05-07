<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex w-full sm:w-auto items-center justify-center rounded-xl bg-emerald-600 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-emerald-400/60 focus:ring-offset-0 transition']) }}>
    {{ $slot }}
</button>
