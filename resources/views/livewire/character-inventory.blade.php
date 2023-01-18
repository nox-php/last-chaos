<div>
    <div class="flex items-center justify-center">
        <div class="relative"
             x-data="{bag: @entangle('tab'), inventoryTooltips: @entangle('inventoryTooltips'), wearingTooltips: @entangle('wearingTooltips')}">
            <img
                src="{{ route('last-chaos.inventory', $this->character->inventory_background) }}"
                alt="Character Inventory"
                style="width: 350px; height: auto;"
            >

            <div class="absolute overflow-y-auto" style="left: 24px; bottom: 8px; height: 257px;">
                <x-last-chaos::character-inventory-bag-button
                    :bag="0"
                />

                <x-last-chaos::character-inventory-bag-button
                    :bag="1"
                />

                <x-last-chaos::character-inventory-bag-button
                    :bag="2"
                />

                <x-last-chaos::character-inventory-bag-button
                    :bag="3"
                />
            </div>

            <x-last-chaos::character-inventory-slots/>
            <x-last-chaos::character-wearing-slots/>

            <div class="absolute" style="right: 29px; top: 269px; width: 216px; height: 19px;">
                <input type="text" class="border-0 text-sm font-medium text-right text-white bg-transparent h-full w-full relative block p-0 px-1" wire:model.lazy="a_nas" />
            </div>
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
