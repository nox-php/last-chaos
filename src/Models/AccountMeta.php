<?php

namespace Nox\LastChaos\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountMeta extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'a_index';

    protected $connection = 'last-chaos';

    protected $fillable = [
        'a_enable',
        'a_zone_num'
    ];

    protected $casts = [
        'a_enable' => 'boolean',
        'a_zone_num' => 'integer'
    ];

    public function getTable(): string
    {
        return config('last-chaos.database.schemas.auth') . '.t_users';
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'a_idname', 'user_id');
    }
}
