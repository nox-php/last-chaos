<?php

namespace Nox\LastChaos\Filament\Resources\CharacterResource\Pages;

use Filament\Pages\Actions\ActionGroup;
use Filament\Pages\Actions\DeleteAction;
use Filament\Pages\Actions\ForceDeleteAction;
use Filament\Pages\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Nox\LastChaos\Filament\Resources\CharacterResource;

class EditCharacter extends EditRecord
{
    protected static string $resource = CharacterResource::class;

    protected function getActions(): array
    {
        return [
            DeleteAction::make(),
            RestoreAction::make(),
            ForceDeleteAction::make()
        ];
    }
}
