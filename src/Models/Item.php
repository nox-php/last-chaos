<?php

namespace Nox\LastChaos\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $connection = 'last-chaos';

    public $timestamps = false;

    protected $primaryKey = 'a_index';

    protected $guarded = [];

    public function getTable(): string
    {
        return config('last-chaos.database.schemas.data') . '.t_item';
    }
}
