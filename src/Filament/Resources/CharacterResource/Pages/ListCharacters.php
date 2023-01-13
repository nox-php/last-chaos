<?php

namespace Nox\LastChaos\Filament\Resources\CharacterResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Nox\LastChaos\Filament\Resources\CharacterResource;

class ListCharacters extends ListRecords
{
    protected static string $resource = CharacterResource::class;
}