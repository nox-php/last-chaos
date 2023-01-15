<?php

namespace Nox\LastChaos\Http\Livewire;

use Closure;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;
use Nox\LastChaos\Models\Character;
use Nox\LastChaos\Models\Item;
use Nox\LastChaos\Support\LastChaos;

class CharacterInventory extends Component implements HasForms
{
    use InteractsWithForms;

    public Character $character;

    public int $tab = 0;

    public int $row = 0;

    public int $column = 0;

    public array $inventory = [];

    public array $tooltips = [];

    public function mount()
    {
        $this->loadInventory();

        $this->form->fill();
    }

    protected function loadInventory()
    {
        $this->inventory = [];

        $records = $this->character->inventory;

        $ids = $records
            ->map(static fn($record): array => [
                $record->a_item_idx0,
                $record->a_item_idx1,
                $record->a_item_idx2,
                $record->a_item_idx3,
                $record->a_item_idx4,
            ])
            ->flatten()
            ->filter(static fn($id): bool => $id !== -1)
            ->unique()
            ->all();

        $items = Item::query()
            ->whereIn('a_index', $ids)
            ->get()
            ->mapWithKeys(static fn($item): array => [
                $item->a_index => $item
            ])
            ->all();

        foreach ($records as $record) {
            for ($column = 0; $column < 5; $column++) {
                $this->inventory[$record->a_tab_idx][$record->a_row_idx][$column] = [
                    'item' => $items[$record->{'a_item_idx' . $column}] ?? null,
                    'quantity' => $record->{'a_count' . $column}
                ];
            }
        }

        $this->inventory = collect($this->inventory)
            ->toArray();

        $this->loadTooltips();

        $this->character->setRelations([]);
    }

    public function loadTooltips(): void
    {
        $this->tooltips = [];

        foreach ($this->inventory as $tabIndex => $tab) {
            foreach ($tab as $rowIndex => $row) {
                foreach ($row as $columnIndex => $column) {
                    if ($column['item'] === null) {
                        $this->tooltips[$tabIndex][$rowIndex][$columnIndex] = false;
                        continue;
                    }

                    $name = empty($column['item']['a_name']) ? $column['item']['a_name_usa'] : $column['item']['a_name'];

                    $this->tooltips[$tabIndex][$rowIndex][$columnIndex] = $name . '(' . $column['quantity'] . ')';
                }
            }
        }
    }

    public function render()
    {
        return view('last-chaos::livewire.character-inventory');
    }

    public function updateSelection(int $tab, int $row, int $column): void
    {
        $this->tab = $tab;
        $this->row = $row;
        $this->column = $column;

        $entry = $this->inventory[$this->tab][$this->row][$this->column] ?? null;
        if ($entry === null || $entry['item'] === null) {
            $this->resetForm();
        } else {
            $this->resetForm($entry['item']['a_index'], $entry['quantity']);
        }

        $this->dispatchBrowserEvent('open-modal', ['id' => 'inventory-selector']);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $this->character->inventory()->updateOrInsert([
            'a_char_idx' => $this->character->a_index,
            'a_tab_idx' => $this->tab,
            'a_row_idx' => $this->row
        ], [
            'a_item_idx' . $this->column => $state['item'] ?? -1,
            'a_count' . $this->column => $state['item'] === null ? 0 : $state['quantity'],
            'a_serial' . $this->column => LastChaos::generateItemSerial($this->character->a_server)
        ]);

        $this->loadInventory();

        $this->resetForm();

        $this->dispatchBrowserEvent('close-modal', ['id' => 'inventory-selector']);
    }

    protected function resetForm(?int $item = null, int $quantity = 1): void
    {
        data_set($this, 'item', $item);
        data_set($this, 'quantity', $quantity);
    }

    protected function getFormSchema(): array
    {
        return [
            Fieldset::make('Item')
                ->schema([
                    Select::make('item')
                        ->label('Item')
                        ->searchable()
                        ->getSearchResultsUsing(fn(string $search) => Item::query()->whereRaw('LOWER(a_name) LIKE LOWER(?)', ['%' . $search . '%'])->limit(50)->pluck('a_name', 'a_index'))
                        ->getOptionLabelUsing(fn($value): ?string => Item::find($value)?->a_name),
                    TextInput::make('quantity')
                        ->label('Quantity')
                        ->numeric()
                        ->minValue(1)
                        ->default(1)
                ])
        ];
    }
}
