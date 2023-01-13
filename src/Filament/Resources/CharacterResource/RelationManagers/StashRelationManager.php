<?php

namespace Nox\LastChaos\Filament\Resources\CharacterResource\RelationManagers;

use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;

class StashRelationManager extends RelationManager
{
    protected static string $relationship = 'stash';

    protected static ?string $recordTitleAttribute = 'a_name';

    public static function form(Form $form): Form
    {
        return parent::form($form);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('a_name')
                    ->label('Name')
            ]);
    }
}