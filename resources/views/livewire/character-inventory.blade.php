<div>
    <div class="flex items-center justify-center">
        <div class="relative" x-data="{tooltips: @entangle('tooltips').defer}">
            <img
                src="{{ route('last-chaos.inventory', $this->character->inventory_background) }}"
                alt="Character Inventory"
                style="width: 350px; height: auto;"
            >
            @php
                $tops = [301, 351, 400, 449, 499];
            @endphp

            @for($rowIndex = 0; $rowIndex < 5; $rowIndex++)
                <x-last-chaos::character-inventory-button
                    :row="$rowIndex"
                    :column="0"
                    :height="45"
                    :width="45"
                    :top="$tops[$rowIndex]"
                    :left="54"
                />

                <x-last-chaos::character-inventory-button
                    :row="1"
                    :column="1"
                    :height="46"
                    :width="46"
                    :top="$tops[$rowIndex]"
                    :left="103"
                />

                <x-last-chaos::character-inventory-button
                    :row="$rowIndex"
                    :column="2"
                    :height="46"
                    :width="45"
                    :top="$tops[$rowIndex]"
                    :left="153"
                />

                <x-last-chaos::character-inventory-button
                    :row="$rowIndex"
                    :column="3"
                    :height="46"
                    :width="46"
                    :top="$tops[$rowIndex]"
                    :left="202"
                />

                <x-last-chaos::character-inventory-button
                    :row="$rowIndex"
                    :column="4"
                    :height="45"
                    :width="45"
                    :top="$tops[$rowIndex]"
                    :left="252"
                />
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
