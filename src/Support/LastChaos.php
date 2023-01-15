<?php

namespace Nox\LastChaos\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

class LastChaos
{
    use Macroable;

    private static ?array $cachedClasses = null;

    public static function generateItemSerial(int $server): string
    {
        return sprintf(
            '%d%02d%02d%04d',
            time(),
            $server,
            1,
            random_int(0, 9999)
        );
    }

    public static function hash(string $username, string $password): string
    {
        $algo = config('last-chaos.auth.hash');

        $method = 'hash' . Str::studly($algo) . 'Password';

        return static::$method($username, $password);
    }

    public static function getAvailableClasses(): array
    {
        return collect(static::getClasses())
            ->pluck('class')
            ->all();
    }

    public static function getClasses(): array
    {
        return static::$cachedClasses ?? (static::$cachedClasses = [
            [
                'class' => 'Titan',
                'jobs' => [
                    'Titan',
                    'Warmaster',
                    'Highlander'
                ]
            ],
            [
                'class' => 'Knight',
                'jobs' => [
                    'Knight',
                    'Royal Knight',
                    'Templar'
                ]
            ],
            [
                'class' => 'Healer',
                'jobs' => [
                    'Healer',
                    'Archer',
                    'Cleric'
                ]
            ],
            [
                'class' => 'Mage',
                'jobs' => [
                    'Mage',
                    'Wizard',
                    'Witch'
                ]
            ],
            [
                'class' => 'Rogue',
                'jobs' => [
                    'Rogue',
                    'Ranger',
                    'Assassin'
                ]
            ],
            [
                'class' => 'Sorcerer',
                'jobs' => [
                    'Sorcerer',
                    'Specialist',
                    'Elementalist'
                ]
            ],
            [
                'class' => 'NightShadow',
                'jobs' => [
                    'NightShadow',
                    'NightShadow',
                    'NightShadow'
                ]
            ],
            [
                'class' => 'Ex-Rogue',
                'jobs' => [
                    'Ex-Rogue',
                    'Ex-Ranger',
                    'Ex-Assassin'
                ]
            ],
            [
                'class' => 'ArchMage',
                'jobs' => [
                    'ArchMage',
                    'ArchWizard',
                    'ArchWitch'
                ]
            ]
        ]);
    }

    public static function getAvailableJobs(int $class): array
    {
        $classes = static::getClasses();

        if (!isset($classes[$class])) {
            return [];
        }

        return $classes[$class]['jobs'];
    }

    protected static function hashPlainTextPassword(string $username, string $password): string
    {
        return $password;
    }

    protected static function hashMd5Password(string $username, string $password): string
    {
        return hash('md5', $password);
    }

    protected static function hashSha256Password(string $username, string $password): string
    {
        $salt = config('last-chaos.auth.salt');

        return hash(
            'sha256',
            $password . $salt . $username
        );
    }
}

