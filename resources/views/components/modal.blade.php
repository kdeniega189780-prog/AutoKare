@props([
    'name' => 'modal',
    'size' => 'lg',
    'title' => null,
])

<div class="modal fade" id="{{ $name }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-{{ $size }}" role="document">
        <div class="modal-content">
            @if ($title)
                <div class="modal-header">
                    <h5 class="modal-title">{{ $title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif
            {{ $slot }}
        </div>
    </div>
</div>
