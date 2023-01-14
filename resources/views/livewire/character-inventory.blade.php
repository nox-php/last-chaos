<div>
    <div class="flex items-center justify-center">
        <div class="relative">
            <img
                    src="{{ route('last-chaos.inventory', $this->character->inventory_background) }}"
                    alt="Character Inventory"
                    style="width: 255px; height: 407px"
            >

            <button
                    class="absolute top-[219px] left-[40px] bg-transparent h-[34px] w-[34px] border border-transparent transition hover:border-primary-600"
            ></button>
        </div>
    </div>

    <form wire:submit.prevent="save">
        <x-filament::modal>
            <x-slot name="header">
                Choose item
            </x-slot>

            {{ $this->form }}

            <x-slot name="actions">
                <x-filament::button type="submit">
                    Save
                </x-filament::button>
            </x-slot>
        </x-filament::modal>
    </form>
</div>
