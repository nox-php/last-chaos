<?php

namespace Nox\LastChaos\Filament\Pages;

use Closure;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Nox\Framework\Admin\Contracts\HasCustomAbilities;
use Nox\Framework\Support\Env;

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
            'max_accounts_per_user' => config('last-chaos.max_accounts_per_user', 1),

            'password_hash' => config('last-chaos.auth.hash', 'sha256'),

            'database_host' => config('last-chaos.database.connection.host'),
            'database_port' => config('last-chaos.database.connection.port'),
            'database_username' => config('last-chaos.database.connection.username'),
            'database_password_empty' => empty(config('last-chaos.database.connection.password')),

            'database_schema_data' => config('last-chaos.database.schemas.data'),
            'database_schema_db' => config('last-chaos.database.schemas.db'),
            'database_schema_auth' => config('last-chaos.database.schemas.auth'),
            'database_schema_post' => config('last-chaos.database.schemas.post'),
        ]);
    }

    protected static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->can('view_last_chaos_settings');
    }

    public function save(): void
    {
        $state = $this->form->getState();

        $env = (new Env())
            ->put('LAST_CHAOS_AUTH_HASH', $state['password_hash'])
            ->put('LAST_CHAOS_AUTH_SALT', $state['password_salt'])
            ->put('LAST_CHAOS_MAX_ACCOUNTS_PER_USER', $state['max_accounts_per_user'])
            ->put('LAST_CHAOS_CONNECTION_HOST', $state['database_host'])
            ->put('LAST_CHAOS_CONNECTION_PORT', $state['database_port'])
            ->put('LAST_CHAOS_CONNECTION_USERNAME', $state['database_username'])
            ->put('LAST_CHAOS_DATABASE_SCHEMA_DATA', $state['database_schema_data'])
            ->put('LAST_CHAOS_DATABASE_SCHEMA_DB', $state['database_schema_db'])
            ->put('LAST_CHAOS_DATABASE_SCHEMA_AUTH', $state['database_schema_auth'])
            ->put('LAST_CHAOS_DATABASE_SCHEMA_POST', $state['database_schema_post']);

        if (array_key_exists('database_password', $state)) {
            $env->put('LAST_CHAOS_CONNECTION_PASSWORD', $state['database_password'] ?? '');
        }

        if($env->save()) {
            Notification::make()
                ->success()
                ->title('Last Chaos')
                ->body('Settings successfully updated')
                ->send();
        } else {
            Notification::make()
                ->danger()
                ->title('Last Chaos')
                ->body('Settings failed to update')
                ->send();
        }
    }

    protected static function getNavigationGroup(): ?string
    {
        return __('last-chaos::groups.last-chaos');
    }

    protected function getFormSchema(): array
    {
        return [
            Tabs::make('Settings')
                ->disableLabel()
                ->tabs([
                    Tabs\Tab::make('Site')
                        ->schema([
                            Grid::make()
                                ->schema([
                                    TextInput::make('max_accounts_per_user')
                                        ->label('Max accounts per user')
                                        ->hint('Use -1 to allow unlimited')
                                        ->helperText('Restrict how many accounts each user can have')
                                        ->numeric()
                                        ->minValue(-1)
                                ])
                        ]),
                    Tabs\Tab::make('Auth')
                        ->schema([
                            Grid::make()
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
                                        ->password()
                                ])
                        ]),
                    Tabs\Tab::make('Database')
                        ->schema([
                            Fieldset::make('Connection')
                                ->schema([
                                    TextInput::make('database_host')
                                        ->label('Database host')
                                        ->required(),
                                    TextInput::make('database_port')
                                        ->label('Database port')
                                        ->required(),
                                    TextInput::make('database_username')
                                        ->label('Database username')
                                        ->required(),
                                    Hidden::make('database_password_empty')
                                        ->default(false)
                                        ->reactive(),
                                    TextInput::make('database_password')
                                        ->label('Database password')
                                        ->password()
                                        ->dehydrated(fn(Closure $get, $state) => filled($state) || $get('database_password_empty'))
                                        ->disabled(static fn(Closure $get) => $get('database_password_empty') === true)
                                        ->suffixAction(static function (Closure $get, Closure $set) {
                                            if ($get('database_password_empty') === true) {
                                                return \Filament\Forms\Components\Actions\Action::make('empty-database-password')
                                                    ->icon('heroicon-o-plus')
                                                    ->action(static function () use ($set) {
                                                        $set('database_password_empty', false);
                                                    });
                                            }

                                            return \Filament\Forms\Components\Actions\Action::make('empty-database-password')
                                                ->icon('heroicon-o-minus')
                                                ->action(static function () use ($set) {
                                                    $set('database_password_empty', true);
                                                });
                                        })
                                ]),
                            Fieldset::make('Schemas')
                                ->schema([
                                    TextInput::make('database_schema_data')
                                        ->label('Data')
                                        ->required(),
                                    TextInput::make('database_schema_db')
                                        ->label('DB')
                                        ->required(),
                                    TextInput::make('database_schema_auth')
                                        ->label('Auth')
                                        ->required(),
                                    TextInput::make('database_schema_post')
                                        ->label('Post')
                                        ->required(),
                                ])
                        ])
                ])
        ];
    }

    protected function getTitle(): string
    {
        return __('last-chaos::pages.settings.label');
    }
}
