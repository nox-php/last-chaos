<?php

namespace Nox\LastChaos\Filament\Resources;

use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Nox\LastChaos\Filament\Resources\CharacterResource\Pages;
use Nox\LastChaos\Models\Character;

class CharacterResource extends Resource
{
    protected static ?string $model = Character::class;

    protected static ?string $slug = 'last-chaos/characters';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                Forms\Components\TextInput::make('a_nick')
                    ->label('Name')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('a_nick')
                    ->label('Name')
            ]);
    }

    public static function getModelLabel(): string
    {
        return __('last-chaos::resources.character.label');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCharacters::route('/'),
            'create' => Pages\CreateCharacter::route('/create'),
            'edit' => Pages\EditCharacter::route('/{record}'),
        ];
    }

    protected static function getNavigationLabel(): string
    {
        return __('last-chaos::resources.character.navigation_label');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('last-chaos::groups.last-chaos');
    }
}