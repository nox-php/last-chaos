<?php

namespace Nox\LastChaos\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = false;
    
    protected $connection = 'last-chaos';

    protected $primaryKey = 'a_index';

    protected $guarded = [];

    public function getTable(): string
    {
        return config('last-chaos.database.schemas.data') . '.t_item';
    }

    public function icon(): Attribute
    {
        return Attribute::make(
            get: fn(): string => route('last-chaos.item.icon', ['id' => $this->a_item_idx])
        );
    }
}
