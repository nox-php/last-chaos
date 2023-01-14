<?php

namespace Nox\LastChaos\Filament\Resources\CharacterResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;

class InventoryRelationManager extends RelationManager
{
    protected static string $view = 'last-chaos::filament.relation-managers.inventory';

    protected static string $relationship = 'inventory_rows';

    protected static ?string $title = 'Inventory';
}