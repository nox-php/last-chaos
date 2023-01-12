<?php

namespace Nox\LastChaos\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Nox\LastChaos\Traits\DelayedDeleting;

class Character extends Model
{
    use DelayedDeleting;

    protected $connection = 'last-chaos';

    public $timestamps = false;

    protected $primaryKey = 'a_index';

    protected $fillable = [
        'a_user_index',
        'a_name',
        'a_nick',
        'a_job',
        'a_job2',
        'a_level',
        'a_admin',
        'a_deleted_delay'
    ];

    protected $casts = [
        'a_createdate' => 'datetime',
        'a_admin' => 'integer',
        'a_deletedelay' => 'integer',
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(
            Account::class,
            'a_user_index',
            'user_code'
        );
    }

    public function getTable(): string
    {
        return config('last-chaos.database.schemas.db') . '.t_characters';
    }
}
