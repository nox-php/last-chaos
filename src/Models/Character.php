<?php

namespace Nox\LastChaos\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Nox\LastChaos\Concerns\DelayedDeletes;
use Nox\LastChaos\Concerns\HasDynamicTables;
use Nox\LastChaos\Support\LastChaos;

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
        'a_skill_point' => 'integer',
        'a_server' => 'integer'
    ];

    public function account(): BelongsTo
    {
        return $this->belongsTo(
            Account::class,
            'a_user_index',
            'user_code',
        );
    }

    public function inventory(): HasMany
    {
        return $this->hasManyDynamic(
            InventoryRow::class,
            config('last-chaos.database.schemas.db') . '.t_inven0' . substr((string)$this->a_index, -1),
            'a_char_idx',
            'a_index'
        );
    }

    public function stash(): BelongsToMany
    {
        return $this->belongsToMany(
            Item::class,
            config('last-chaos.database.schemas.db') . '.t_stash0' . substr((string)$this->a_user_index, -1),
            'a_user_idx',
            'a_item_idx',
            'a_user_index',
            'a_index',
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

    public function class(): Attribute
    {
        return Attribute::make(
            get: fn() => LastChaos::getClasses()[$this->a_job]['class']
        );
    }

    public function job(): Attribute
    {
        return Attribute::make(
            get: fn() => LastChaos::getClasses()[$this->a_job]['jobs'][$this->a_job2]
        );
    }

    public function inventoryBackground(): Attribute
    {
        return Attribute::make(
            get: function() {
                $class = $this->class;
                if($class === 'Ex-Rogue') {
                    $class = 'Rogue';
                }

                return $class;
            }
        );
    }
}
