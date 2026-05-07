@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'mt-2 space-y-1 text-sm text-red-200']) }}>
        @foreach ((array) $messages as $message)
            <li class="leading-tight">{{ $message }}</li>
        @endforeach
    </ul>
@endif
