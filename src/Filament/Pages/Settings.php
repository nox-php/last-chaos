<?php

namespace Nox\LastChaos\Filament\Pages;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Nox\Framework\Admin\Contracts\HasCustomAbilities;

class Settings extends Page implements HasCustomAbilities
{
    protected static string $view = 'last-chaos::filament.pages.settings';

    protected static ?string $slug = 'last-chaos/settings';

    protected static ?int $navigationSort = 10;

    public static function getNavigationLabel(): string
    {
        return __('last-chaos::pages.settings.label');
    }

    public static function getCustomAbilities(): array
    {
        return [
            'view_last_chaos_settings',
        ];
    }

    public function mount(): void
    {
        abort_unless(static::shouldRegisterNavigation(), 401);

        $this->form->fill([
            'password_hash' => config('last-chaos.auth.hash', 'sha256'),
            'password_salt' => config('last-chaos.auth.salt', ''),

            'max_accounts_per_user' => config('last-chaos.max_accounts_per_user', 1)
        ]);
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view_last_chaos_settings');
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('last-chaos::groups.last-chaos');
    }

    protected function getFormSchema(): array
    {
        return [
            Card::make()
                ->schema([
                    Fieldset::make('Authentication')
                        ->schema([
                            Select::make('password_hash')
                                ->label('Password hash')
                                ->required()
                                ->options([
                                    'plaintext' => 'Plaintext',
                                    'md5' => 'MD5',
                                    'sha256' => 'Sha256'
                                ]),
                            TextInput::make('password_salt')
                                ->label('Salt')
                        ]),
                    TextInput::make('max_accounts_per_user')
                        ->label('Max accounts per user')
                        ->hint('Use -1 to allow unlimited')
                        ->helperText('Restrict how many accounts each user can have')
                        ->numeric()
                        ->minValue(-1)
                ])
        ];
    }

    protected function getTitle(): string
    {
        return __('last-chaos::pages.settings.label');
    }
}