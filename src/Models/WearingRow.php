<?php

namespace Nox\LastChaos\Models;

use Illuminate\Database\Eloquent\Model;

class WearingRow extends Model
{
    public $timestamps = false;

    protected $connection = 'last-chaos';

    protected $guarded = [];

    public function getTable(): string
    {
        return config('last-chaos.database.schemas.db') . '.t_wear_inven';
    }
}
