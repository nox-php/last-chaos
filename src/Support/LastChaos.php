<?php

namespace Nox\LastChaos\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

class LastChaos
{
    use Macroable;

    public static function hash(string $username, string $password): string
    {
        $algo = config('last-chaos.auth.hash');

        $method = 'hash' . Str::studly($algo) . 'Password';

        return static::$method($username, $password);
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

