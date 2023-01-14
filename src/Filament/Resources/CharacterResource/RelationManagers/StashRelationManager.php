<?php

namespace Nox\LastChaos\Filament\Resources\CharacterResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Nox\LastChaos\Models\Item;

class StashRelationManager extends RelationManager
{
    protected static string $view = 'last-chaos::filament.relation-managers.stash';

    protected static string $relationship = 'stash';

    protected static ?string $recordTitleAttribute = 'a_name';

    protected bool $allowsDuplicates = true;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('icon')
                    ->label('Icon'),
                TextColumn::make('a_name')
                    ->label('Name')
                    ->formatStateUsing(static fn(Item $record): string => empty($record->a_name) ? $record->a_name_usa : $record->a_name),
                TextColumn::make('a_descr')
                    ->label('Description')
                    ->formatStateUsing(static fn(Item $record): string => empty($record->a_descr) ? $record->a_descr_usa : $record->a_descr),
                BadgeColumn::make('a_count')
                    ->label('Quantity')
                    ->color('success')
                    ->formatStateUsing(static fn($state) => number_format($state))
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordSelectOptionsQuery(static fn(Builder $query): Builder => $query->where(
                        'a_enable',
                        '=',
                        1
                    ))
                    ->mutateFormDataUsing(fn($livewire, array $data) => [
                        ...$data,
                        'a_serial' => sprintf(
                            '%d%02d%02d%04d',
                            time(),
                            $livewire->getOwnerRecord()->a_server,
                            1,
                            random_int(0, 9999)
                        )
                    ])
                    ->form(static fn(AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('a_count')
                            ->label('Quantity')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                    ])
            ])
            ->actions([
                EditAction::make(),
                DetachAction::make()
            ])
            ->bulkActions([
                DetachBulkAction::make()
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('a_count')
                    ->label('Quantity')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(1)
            ]);
    }

    public static function getRecordTitle(?Model $record): ?string
    {
        if (!empty($record->a_name)) {
            return $record->a_name;
        }

        return $record->a_name_usa;
    }
}
