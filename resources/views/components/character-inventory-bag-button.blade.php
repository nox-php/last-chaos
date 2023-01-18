@props([
    'bag'
])

<div
    style="width: 21px; height: 47px; margin-bottom: 5px;"
>
    <button
        class="w-full h-full"
        wire:click.prevent="setBag({{ $bag }})"
    >
        <img
            src="{{ route('last-chaos.buttons', 'active') }}"
            alt="Bag 1"
            class="h-full w-full"
            x-show="bag === {{ $bag }}"
        />

        <img
            src="{{ route('last-chaos.buttons', 'inactive') }}"
            alt="Bag 1"
            class="h-full w-full"
            x-show="bag !== {{ $bag }}"
        />
    </button>
</div>
