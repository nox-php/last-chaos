<?php

namespace Nox\LastChaos\Http\Livewire;

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

    public array $inventoryTooltips = [];

    public bool $selectedWearing = false;

    public int $wearingIndex = 0;

    public array $wearing = [];

    public array $wearingTooltips = [];

    public function mount()
    {
        $this->loadInventory();

        $this->form->fill();
    }

    protected function loadInventory()
    {
        $this->inventory = [];
        $this->wearing = [];

        $ids = $this->character->inventory
            ->map(static fn($record): array => [
                $record->a_item_idx0,
                $record->a_item_idx1,
                $record->a_item_idx2,
                $record->a_item_idx3,
                $record->a_item_idx4,
            ])
            ->flatten()
            ->merge($this->character->wearing->pluck('a_item_idx'))
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

        foreach ($this->character->inventory as $record) {
            for ($column = 0; $column < 5; $column++) {
                $this->inventory[$record->a_tab_idx][$record->a_row_idx][$column] = [
                    'item' => $items[$record->{'a_item_idx' . $column}] ?? null,
                    'quantity' => $record->{'a_count' . $column}
                ];
            }
        }

        foreach ($this->character->wearing as $record) {
            $this->wearing[$record->a_wear_pos] = [
                'item' => $items[$record->{'a_item_idx'}] ?? null
            ];
        }

        $this->inventory = collect($this->inventory)
            ->toArray();

        $this->wearing = collect($this->wearing)
            ->toArray();

        $this->loadTooltips();

        $this->character->setRelations([]);
    }

    public function loadTooltips(): void
    {
        $this->inventoryTooltips = [];
        $this->wearingTooltips = [];

        foreach ($this->inventory as $tabIndex => $tab) {
            foreach ($tab as $rowIndex => $row) {
                foreach ($row as $columnIndex => $column) {
                    if ($column['item'] === null) {
                        $this->inventoryTooltips[$tabIndex][$rowIndex][$columnIndex] = false;
                        continue;
                    }

                    $name = empty($column['item']['a_name']) ? $column['item']['a_name_usa'] : $column['item']['a_name'];

                    $this->inventoryTooltips[$tabIndex][$rowIndex][$columnIndex] = $name . '(' . $column['quantity'] . ')';
                }
            }
        }

        foreach($this->wearing as $id => $record) {
            if($record['item'] === null) {
                $this->wearingTooltips[$id] = false;
                continue;
            }

            $name = empty($record['item']['a_name']) ? $record['item']['a_name_usa'] : $record['item']['a_name'];

            $this->wearingTooltips[$id] = $name;
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
        $this->selectedWearing = false;

        $entry = $this->inventory[$this->tab][$this->row][$this->column] ?? null;
        if ($entry === null || $entry['item'] === null) {
            $this->resetForm();
        } else {
            $this->resetForm($entry['item']['a_index'], $entry['quantity']);
        }

        $this->dispatchBrowserEvent('open-modal', ['id' => 'inventory-selector']);
    }

    protected function resetForm(?int $item = null, int $quantity = 1): void
    {
        data_set($this, 'item', $item);
        data_set($this, 'quantity', $quantity);
    }

    public function updateWearingSelection(int $index): void
    {
        $this->wearingIndex = $index;
        $this->selectedWearing = true;

        $entry = $this->wearing[$index] ?? null;
        if ($entry === null || $entry['item'] === null) {
            $this->resetForm();
        } else {
            $this->resetForm($entry['item']['a_index']);
        }

        $this->dispatchBrowserEvent('open-modal', ['id' => 'inventory-selector']);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        if ($this->selectedWearing) {
            if($state['item'] === null) {
                $this->character->wearing()
                    ->where('a_char_index', '=', $this->character->a_index)
                ->where('a_wear_pos', '=', $this->wearingIndex)
                ->delete();
            } else {
                $this->character->wearing()->updateOrInsert([
                    'a_char_index' => $this->character->a_index,
                    'a_wear_pos' => $this->wearingIndex
                ], [
                    'a_item_idx' => $state['item'],
                    'a_serial' => LastChaos::generateItemSerial($this->character->a_server)
                ]);
            }
        } else {
            $this->character->inventory()->updateOrInsert([
                'a_char_idx' => $this->character->a_index,
                'a_tab_idx' => $this->tab,
                'a_row_idx' => $this->row
            ], [
                'a_item_idx' . $this->column => $state['item'] ?? -1,
                'a_count' . $this->column => $state['item'] === null ? 0 : $state['quantity'],
                'a_serial' . $this->column => LastChaos::generateItemSerial($this->character->a_server)
            ]);
        }

        $this->loadInventory();

        $this->resetForm();

        $this->dispatchBrowserEvent('close-modal', ['id' => 'inventory-selector']);
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
                        ->hidden(fn() => $this->selectedWearing)
                ])
        ];
    }
}
