<?php

namespace Nox\LastChaos\Models;

use Illuminate\Database\Eloquent\Model;

class StashMoney extends Model
{
    public $timestamps = false;

    protected $connection = 'last-chaos';

    protected $guarded = [];

    protected $casts = [
        'a_stash_money' => 'integer'
    ];

    public function getTable(): string
    {
        return config('last-chaos.database.schemas.db') . '.t_stash_money';
    }
}
