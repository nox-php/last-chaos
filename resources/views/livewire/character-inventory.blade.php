<div>
    <div class="flex items-center justify-center">
        <div class="relative" x-data="{inventoryTooltips: @entangle('inventoryTooltips').defer, wearingTooltips: @entangle('wearingTooltips').defer}">
            <img
                src="{{ route('last-chaos.inventory', $this->character->inventory_background) }}"
                alt="Character Inventory"
                style="width: 350px; height: auto;"
            >

            <div class="absolute left-0 bottom-0 h-6 w-6" style="background: red;"></div>

            <x-last-chaos::character-inventory-slots />
            <x-last-chaos::character-wearing-slots />
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
