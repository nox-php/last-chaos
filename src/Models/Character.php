<?php

namespace Nox\LastChaos\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Nox\LastChaos\Concerns\DelayedDeletes;
use Nox\LastChaos\Concerns\HasDynamicTables;
use Nox\LastChaos\Enums\CharacterClass;
use Nox\LastChaos\Enums\CharacterJob;

class Character extends Model
{
    use DelayedDeletes, HasDynamicTables;

    public $timestamps = false;

    protected $connection = 'last-chaos';

    protected $primaryKey = 'a_index';

    protected $guarded = [];

    protected $casts = [
        'a_createdate' => 'datetime',
        'a_levelup_date' => 'datetime',
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

    public function stash(): BelongsToMany
    {
        return $this->belongsToMany(
            Item::class,
            config('last-chaos.database.schemas.db') . '.t_stash0' . substr((string)$this->a_user_index, -1),
            foreignPivotKey: 'a_user_idx',
            relatedPivotKey: 'a_item_idx',
            relatedKey: 'a_index',
        )
            ->using(Stash::class)
            ->withPivot([
                'a_plus',
                'a_wear_pos',
                'a_flag',
                'a_serial',
                'a_count',
                'a_used',
                'a_item_option0',
                'a_item_option1',
                'a_item_option2',
                'a_item_option3',
                'a_item_option4',
                'a_used_2',
                'a_socket',
                'a_item_origin_var0',
                'a_item_origin_var1',
                'a_item_origin_var2',
                'a_item_origin_var3',
                'a_item_origin_var4',
                'a_item_origin_var5',
                'a_now_dur',
                'a_max_dur'
            ]);
    }

    public function getTable(): string
    {
        return config('last-chaos.database.schemas.db') . '.t_characters';
    }

    public function characterClass(): Attribute
    {
        return Attribute::make(
            get: fn() => CharacterClass::tryFrom($this->a_job),
            set: function (CharacterClass|int $value) {
                if ($value instanceof CharacterClass) {
                    $this->a_job = $value->value;
                } else {
                    $this->a_job = $value;
                }
            }
        );
    }

    public function characterJob(): Attribute
    {
        return Attribute::make(
            get: fn() => CharacterJob::character($this),
            set: function (CharacterJob|int $value) {
                if ($value instanceof CharacterJob) {
                    $this->a_job2 = explode('.', $value->value)[1];
                } else {
                    $this->a_job2 = $value;
                }
            }
        );
    }
}
