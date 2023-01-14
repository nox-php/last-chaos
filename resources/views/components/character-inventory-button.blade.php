@props([
    'tab',
    'row',
    'column',
    'style'
])

@php
    $item = $this->inventory[$tab][$row][$column]['item'] ?? null;
    $name = $item === null ? null : (empty($item['a_name']) ? $item['a_name_usa'] :  $item['a_name']);
@endphp

<div>
    <button
            {{ $attributes->merge([
                'class' => 'flex items-center justify-center absolute bg-transparent border-2 border-transparent transition hover:border-primary-600',
                'style' => 'width: 53px; height: 53px; ' . $style
            ]) }}
            x-tooltip="tooltips[{{ $tab }}][{{ $row }}][{{ $column }}]"
            wire:click.prevent="updateSelection({{ $tab }}, {{ $row }}, {{ $column }})"
    >
        @if($item !== null)
            <img
                    src="{{ route('last-chaos.item.icon', $item['a_index']) }}"
                    alt="{{ $name }}"
                    class="h-full w-full"
                    style="width: 53px; height: 53px;"
            >
        @endif
    </button>
</div>
