<?php

namespace Nox\LastChaos\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Nox\Framework\Auth\Models\User;
use Nox\LastChaos\Filament\Resources\AccountResource\Pages;
use Nox\LastChaos\Filament\Resources\AccountResource\RelationManagers\CharacterRelationManager;
use Nox\LastChaos\Models\Account;
use Nox\LastChaos\Support\LastChaos;

class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $slug = 'last-chaos/accounts';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 1;

    public static function getModelLabel(): string
    {
        return __('last-chaos::resources.account.label');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns([
                'sm' => 3,
                'lg' => null,
            ])
            ->schema([
                Forms\Components\Card::make()
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan([
                        'sm' => 2,
                    ])
                    ->schema([
                        Forms\Components\TextInput::make('user_id')
                            ->label('Username')
                            ->required()
                            ->maxLength(30)
                            ->unique(ignorable: static fn(?Account $record): ?Account => $record)
                            ->columnSpan(2),
                        Forms\Components\TextInput::make('passwd')
                            ->label('Password')
                            ->password()
                            ->confirmed()
                            ->dehydrateStateUsing(fn(Closure $get, ?string $state) => LastChaos::hash($get('user_id'), $state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $context): bool => $context === 'create')
                            ->minLength(4)
                            ->maxLength(32),
                        Forms\Components\TextInput::make('passwd_confirmation')
                            ->label('Confirm password')
                            ->password()
                    ]),
                Forms\Components\Card::make()
                    ->columnSpan(1)
                    ->schema([
                        Forms\Components\Select::make('user')
                            ->label('Discord')
                            ->relationship('user', User::getUsernameColumnName())
                            ->preload()
                            ->required(),
                        Forms\Components\Placeholder::make('create_date')
                            ->label('Created at')
                            ->content(static fn(?Account $record): string => $record?->create_date?->diffForHumans() ?? '-'),
                        Forms\Components\Placeholder::make('update_time')
                            ->label('Updated at')
                            ->content(static fn(?Account $record): string => $record?->update_time?->diffForHumans() ?? '-'),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('update_time', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                    ->label('Username')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('characters_count')
                    ->label('Characters')
                    ->counts('characters')
                    ->color('primary'),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->enum([
                        'online' => 'Online',
                        'offline' => 'Offline',
                        'banned' => 'Banned'
                    ])
                    ->colors([
                        'success' => 'online',
                        'secondary' => 'offline',
                        'danger' => 'banned'
                    ])
                    ->icons([
                        'heroicon-o-check' => 'online',
                        'heroicon-o-minus-sm' => 'offline',
                        'heroicon-o-x' => 'banned'
                    ]),
                Tables\Columns\TextColumn::make('create_date')
                    ->label('Created at')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('update_time')
                    ->label('Updated at')
                    ->date()
                    ->sortable()
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('ban-account')
                        ->label('Ban')
                        ->icon('heroicon-o-x')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action('banAccount')
                        ->hidden(static fn(Account $record): bool => $record->is_banned),
                    Tables\Actions\Action::make('unban-account')
                        ->label('Un-ban')
                        ->icon('heroicon-o-check')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->action('unbanAccount')
                        ->hidden(static fn(Account $record): bool => !$record->is_banned),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('ban-accounts')
                    ->label('Ban selected')
                    ->icon('heroicon-o-x')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action('banAccounts'),
                Tables\Actions\BulkAction::make('unban-accounts')
                    ->label('Un-ban selected')
                    ->icon('heroicon-o-check')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action('unbanAccounts')
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CharacterRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'edit' => Pages\EditAccount::route('/{record}'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'meta'
        ]);
    }

    protected static function getNavigationLabel(): string
    {
        return __('last-chaos::resources.account.navigation_label');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('last-chaos::groups.last-chaos');
    }
}
