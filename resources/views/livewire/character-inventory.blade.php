<div>
    <div class="flex items-center justify-center">
        <div class="relative" x-data="{tooltips: @entangle('tooltips').defer}">
            <img
                src="{{ route('last-chaos.inventory', $this->character->inventory_background) }}"
                alt="Character Inventory"
                style="width: 400px; height: auto;"
            >

            @php
                $top = 343;
            @endphp

            @for($rowIndex = 0; $rowIndex < 5; $rowIndex++)
                @php
                    $left = 60;
                @endphp

                @for($columnIndex = 0; $columnIndex < 5; $columnIndex++)
                    <x-last-chaos::character-inventory-button
                        tab="0"
                        :row="$rowIndex"
                        :column="$columnIndex"
                        style="top: {{ $top }}px; left: {{ $left }}px;"
                    />

                    @php
                        $left += 57;
                    @endphp
                @endfor

                @php
                    $top += 56;
                @endphp
            @endfor
        </div>
    </div>

    <form wire:submit.prevent="save">
        <x-filament::modal id="inventory-selector" width="6xl">
            <x-slot name="header">
                Choose item
            </x-slot>

            {{ $this->form }}

            <x-slot name="actions">
                <x-filament::button type="submit" wire:loading.attr="disabled">
                    Save
                </x-filament::button>
            </x-slot>
        </x-filament::modal>
    </form>
</div>
