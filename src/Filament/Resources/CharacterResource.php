<?php

namespace Nox\LastChaos\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Nox\LastChaos\Filament\Resources\CharacterResource\Pages;
use Nox\LastChaos\Filament\Resources\CharacterResource\RelationManagers\StashRelationManager;
use Nox\LastChaos\Models\Character;
use Nox\LastChaos\Scopes\DelayedDeletingScope;

class CharacterResource extends Resource
{
    protected static ?string $model = Character::class;

    protected static ?string $slug = 'last-chaos/characters';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('a_nick')
                    ->label('Name'),
                Forms\Components\TextInput::make('a_admin')
                    ->label('Admin level')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('a_nick')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('a_admin')
                    ->label('Admin level')
                    ->color('success')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('a_createdate')
                    ->label('Created at')
                    ->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('a_deletedelay')
                    ->label('Deleting in')
                    ->formatStateUsing(static fn(int $state): string => Carbon::parse($state)->diffForHumans())
                    ->hidden(static fn($livewire) => $livewire->getTableFilterState('trashed')['value'] == '0')
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getModelLabel(): string
    {
        return __('last-chaos::resources.character.label');
    }

    public static function getRelations(): array
    {
        return [
            StashRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCharacters::route('/'),
            'create' => Pages\CreateCharacter::route('/create'),
            'edit' => Pages\EditCharacter::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                DelayedDeletingScope::class,
            ]);
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
