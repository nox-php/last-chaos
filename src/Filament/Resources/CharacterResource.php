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
            ->columns([
                'sm' => 3,
                'lg' => null,
            ])
            ->schema([
                Forms\Components\Tabs::make('Character')
                    ->disableLabel()
                    ->columnSpanFull()
                    ->schema([
                        Forms\Components\Tabs\Tab::make('Basic')
                            ->schema([
                                Forms\Components\Grid::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('a_nick')
                                            ->label('Name'),
                                        Forms\Components\TextInput::make('a_level')
                                            ->label('Level'),
                                        Forms\Components\TextInput::make('a_admin')
                                            ->label('Admin level'),
                                    ])
                            ]),
                        Forms\Components\Tabs\Tab::make('Stats')
                            ->schema([
                                Forms\Components\Fieldset::make('Standard')
                                    ->schema([
                                        Forms\Components\TextInput::make('a_statpt_remain')
                                            ->label('Free stats')
                                            ->required()
                                            ->minValue(0)
                                            ->default(0),
                                        Forms\Components\TextInput::make('a_statpt_str')
                                            ->label('Strength')
                                            ->required()
                                            ->minValue(0)
                                            ->default(0),
                                        Forms\Components\TextInput::make('a_statpt_dex')
                                            ->label('Dexterity')
                                            ->required()
                                            ->minValue(0)
                                            ->default(0),
                                        Forms\Components\TextInput::make('a_statpt_int')
                                            ->label('Intelligence')
                                            ->required()
                                            ->minValue(0)
                                            ->default(0),
                                        Forms\Components\TextInput::make('a_statpt_con')
                                            ->label('Constitution')
                                            ->required()
                                            ->minValue(0)
                                            ->default(0)
                                    ]),
                                Forms\Components\Fieldset::make('Other')
                                    ->schema([
                                        Forms\Components\TextInput::make('a_hp')
                                            ->label('Current health')
                                            ->required()
                                            ->minValue(0)
                                            ->default(0),
                                        Forms\Components\TextInput::make('a_max_hp')
                                            ->label('Max health')
                                            ->required()
                                            ->minValue(1)
                                            ->default(1),
                                        Forms\Components\TextInput::make('a_mp')
                                            ->label('Current mana')
                                            ->required()
                                            ->minValue(0)
                                            ->default(0),
                                        Forms\Components\TextInput::make('a_max_mp')
                                            ->label('Max mana')
                                            ->required()
                                            ->minValue(1)
                                            ->default(1),
                                    ])
                            ])
                    ]),
                Forms\Components\Card::make()
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Placeholder::make('account.user.discord_name')
                            ->label('Discord name')
                            ->content(static fn(?Character $record): string => $record?->account?->user?->discord_name ?? '-'),
                        Forms\Components\Placeholder::make('account.name')
                            ->label('Account'),
                        Forms\Components\Placeholder::make('a_createdate')
                            ->label('Created at')
                            ->content(static fn(?Character $record): string => $record?->a_createdate?->diffForHumans() ?? '-'),
                        Forms\Components\Placeholder::make('a_levelup_date')
                            ->label('Last leveled up at')
                            ->content(static fn(?Character $record): string => $record?->a_levelup_date?->diffForHumans() ?? '-'),
                    ])
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
                Tables\Columns\TextColumn::make('a_level')
                    ->label('Level')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('a_admin')
                    ->label('Admin level')
                    ->color('primary')
                    ->sortable(),
                Tables\Columns\TextColumn::make('a_statpt_remain')
                    ->label('Free')
                    ->sortable(),
                Tables\Columns\TextColumn::make('a_statpt_str')
                    ->label('Str')
                    ->sortable(),
                Tables\Columns\TextColumn::make('a_statpt_dex')
                    ->label('Dex')
                    ->sortable(),
                Tables\Columns\TextColumn::make('a_statpt_int')
                    ->label('Int')
                    ->sortable(),
                Tables\Columns\TextColumn::make('a_statpt_con')
                    ->label('Con')
                    ->sortable(),
                Tables\Columns\TextColumn::make('a_reborn')
                    ->label('Reborn')
                    ->sortable(),
                Tables\Columns\TextColumn::make('a_createdate')
                    ->label('Created at')
                    ->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('a_deletedelay')
                    ->label('Deleting at')
                    ->formatStateUsing(static fn(int $state): string => $state === 0 ? '-' : Carbon::parse($state)->diffForHumans())
                    ->tooltip(static fn(int $state): ?string => $state === 0 ? null : Carbon::parse($state))
                    ->hidden(static function ($livewire) {
                        $filter = $livewire->getTableFilterState('trashed')['value'];

                        if ($filter === '0') {
                            return false;
                        }

                        return empty($filter);
                    })
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
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
