<?php

namespace Nox\LastChaos\Filament\Resources;

use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Nox\LastChaos\Filament\Resources\AccountResource\Pages;
use Nox\LastChaos\Models\Account;

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
        return $form;
    }

    public static function table(Table $table): Table
    {
        return $table;
    }

    protected static function getNavigationLabel(): string
    {
        return __('last-chaos::resources.account.navigation_label');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('last-chaos::groups.last-chaos');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'edit' => Pages\EditAccount::route('/{record}'),
        ];
    }
}