<?php

namespace Nox\LastChaos;

use Filament\PluginServiceProvider;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Nox\Framework\Auth\Models\User;
use Nox\LastChaos\Filament\RelationManagers\AccountRelationManager;
use Nox\LastChaos\Filament\Resources\AccountResource;
use Nox\LastChaos\Models\Account;

class LastChaosServiceProvider extends PluginServiceProvider
{
    public static string $name = 'last-chaos';

    protected array $resources = [
        AccountResource::class
    ];

    protected array $relationManagers = [
        AccountRelationManager::class
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
    }

    public function packageBooted(): void
    {
        parent::packageBooted();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

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
