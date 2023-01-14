<?php

namespace Nox\LastChaos\Http\Livewire;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Nox\LastChaos\Models\Character;

class CharacterInventory extends Component implements HasForms
{
    use InteractsWithForms;

    public Character $character;

    public function render()
    {
        return view('last-chaos::livewire.character-inventory');
    }

    protected function getFormSchema(): array
    {
        return [
            Grid::make()
                ->schema([
                    Select::make('chosen')
                ])
        ];
    }
}
