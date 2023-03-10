<?php

namespace Nox\LastChaos;

use Filament\Facades\Filament;
use Filament\PluginServiceProvider;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Livewire\Livewire;
use Nox\Framework\Auth\Models\User;
use Nox\LastChaos\Filament\Pages\Settings;
use Nox\LastChaos\Filament\RelationManagers\AccountRelationManager;
use Nox\LastChaos\Filament\Resources\AccountResource;
use Nox\LastChaos\Filament\Resources\CharacterResource;
use Nox\LastChaos\Http\Livewire\CharacterInventory;
use Nox\LastChaos\Models\Account;

class LastChaosServiceProvider extends PluginServiceProvider
{
    public static string $name = 'last-chaos';

    protected array $resources = [
        AccountResource::class,
        CharacterResource::class
    ];

    protected array $pages = [
        Settings::class
    ];

    protected array $relationManagers = [
        AccountRelationManager::class
    ];

    protected array $styles = [
        'last-chaos' => __DIR__ . '/../dist/css/last-chaos.css'
    ];

    public function packageRegistered(): void
    {
        parent::packageRegistered();

        config()->set(
            'database.connections.last-chaos',
            [
                ...config('database.connections.mysql', []),
                ...config('last-chaos.database.connection'),
                'database' => ''
            ]
        );

        User::resolveRelationUsing('accounts', static function (User $model): HasMany {
            return $model->hasMany(Account::class, 'nox_user_id');
        });

        $this->app->resolving('filament', function () {
            Filament::serving(static function () {
                Filament::registerNavigationGroups([
                    __('last-chaos::groups.last-chaos') => 0
                ]);
            });
        });
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        Livewire::component('last-chaos::character-inventory', CharacterInventory::class);

        transformer_register(
            'nox.user.resource.relations',
            static function (array $value) {
                return [
                    ...$value,
                    AccountRelationManager::class
                ];
            }
        );
    }
}
