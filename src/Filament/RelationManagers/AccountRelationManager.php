<?php

namespace Nox\LastChaos\Filament\RelationManagers;

use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Resources\Table;
use Filament\Tables;
use Nox\LastChaos\Models\Account;
use Nox\LastChaos\Support\LastChaos;

class AccountRelationManager extends RelationManager
{
    protected static string $relationship = 'accounts';

    protected static ?string $title = 'Last Chaos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->label('Username')
                    ->required()
                    ->maxLength(30),
                Forms\Components\TextInput::make('passwd')
                    ->label('Password')
                    ->password()
                    ->confirmed()
                    ->dehydrateStateUsing(
                        fn(?string $state, callable $get): string => LastChaos::hash($get('user_id'), $state)
                    )
                    ->dehydrated(fn(?string $state) => filled($state))
                    ->required(fn(string $context): bool => $context === 'create'),
                Forms\Components\TextInput::make('passwd_confirmation')
                    ->label('Password confirmation')
                    ->password()
                    ->required(fn(string $context): bool => $context === 'create')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\BadgeColumn::make('user_id')
                    ->label('Username')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('is_banned')
                    ->label('Status')
                    ->enum([
                        true => 'Banned',
                        false => 'Active'
                    ])
                    ->colors([
                        'danger' => true,
                        'success' => false
                    ]),
                Tables\Columns\TextColumn::make('create_date')
                    ->label('Created at')
                    ->date(),
                Tables\Columns\TextColumn::make('last_login_date')
                    ->date('Last logged in')
                    ->date()
            ])
            ->actions([
                Tables\Actions\Action::make('unban')
                    ->label('Un-ban')
                    ->icon('heroicon-o-check')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action('unbanAccount')
                    ->hidden(static fn(Account $record): bool => !$record->is_banned),
                Tables\Actions\Action::make('ban')
                    ->label('Ban')
                    ->icon('heroicon-o-x')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action('banAccount')
                    ->hidden(static fn(Account $record): bool => $record->is_banned),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public function unbanAccount(Account $record)
    {
        $record->unban();

        Notification::make()
            ->success()
            ->title('Account un-banned')
            ->body('LastChaos account has been successfully un-banned')
            ->send();
    }

    public function banAccount(Account $record)
    {
        $record->ban();

        Notification::make()
            ->success()
            ->title('Account banned')
            ->body('LastChaos account has been successfully banned')
            ->send();
    }
}
