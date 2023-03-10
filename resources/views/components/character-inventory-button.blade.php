@props([
    'row',
    'column',
    'height',
    'width',
    'top',
    'left'
])

@php
    $item = $this->inventory[$this->tab][$row][$column]['item'] ?? null;
    $name = $item === null ? null : (empty($item['a_name']) ? $item['a_name_usa'] :  $item['a_name']);
@endphp

<div>
    <button
            {{ $attributes->merge([
                'class' => 'flex items-center justify-center absolute bg-transparent ring-2 ring-transparent transition hover:ring-primary-700',
                'style' => 'width: ' . $width . 'px; height: ' . $height . 'px; top: ' . $top . 'px; left: ' . $left . 'px;'
            ]) }}
            @if($item !== null) x-tooltip="inventoryTooltips[{{ $this->tab }}][{{ $row }}][{{ $column }}]" @endif
            wire:click.prevent="updateSelection({{ $row }}, {{ $column }})"
    >
        @if($item !== null)
            <img
                    src="{{ route('last-chaos.item.icon', $item['a_index']) }}"
                    alt="{{ $name }}"
                    class="h-full w-full"
            />
        @endif
    </button>
</div>
