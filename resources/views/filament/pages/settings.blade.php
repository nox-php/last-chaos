<x-filament::page>
    <form wire:submit.prevent="save" class="filament-form space-y-6">
        {{ $this->form }}

        <div class="filament-page-actions flex flex-wrap items-center gap-4 justify-start filament-form-actions">
        <x-filament::button type="submit" wire:loading.attr="disabled">
            {{ __('nox::admin.pages.settings.actions.save') }}
        </x-filament::button>

        <x-filament::button tag="a" href="#" color="secondary">
            {{ __('nox::admin.pages.settings.actions.cancel') }}
        </x-filament::button>
        </div>
    </form>
</x-filament::page>
