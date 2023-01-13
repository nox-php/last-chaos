<?php

namespace Nox\LastChaos\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class Stash extends Pivot
{
    public $timestamps = false;

    protected $connection = 'last-chaos';

    protected $primaryKey = 'a_index';
}
