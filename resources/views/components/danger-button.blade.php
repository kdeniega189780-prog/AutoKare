<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-red-600/20 hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-400/60 transition']) }}>
    {{ $slot }}
</button>
