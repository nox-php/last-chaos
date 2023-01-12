<?php

namespace Nox\LastChaos\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Nox\Framework\Auth\Models\User;

class Account extends Model
{
    public const CREATED_AT = 'create_date';

    public const UPDATED_AT = 'update_time';

    protected $primaryKey = 'user_code';

    protected $connection = 'last-chaos';

    protected $fillable = [
        'user_id',
        'email',
        'passwd'
    ];

    protected $casts = [
        'last_login_date' => 'datetime'
    ];

    protected $hidden = [
        'passwd'
    ];

    public function getTable(): string
    {
        return config('last-chaos.database.schemas.auth') . '.bg_user';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nox_user_id');
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class, 'a_user_index', 'user_code');
    }

    public function meta(): HasOne
    {
        return $this->hasOne(AccountMeta::class, 'a_idname', 'user_id');
    }

    public function ban(): void
    {
        $this->meta?->forceFill([
            'a_enable' => false
        ])->save();
    }

    public function unban(): void
    {
        $this->meta?->forceFill([
            'a_enable' => true
        ])->save();
    }

    public function isBanned(): Attribute
    {
        return Attribute::make(
            get: function () {
                $meta = $this->meta;
                if ($meta === null) {
                    return false;
                }

                return !$meta->a_enable;
            }
        );
    }

    public function isOnline(): Attribute
    {
        return Attribute::make(
            get: function () {
                $meta = $this->meta;
                if ($meta === null) {
                    return false;
                }

                return $meta->a_zone_num !== -1;
            }
        );
    }

    public function status(): Attribute
    {
        return Attribute::make(
            get: function() {
                if($this->is_banned) {
                    return 'banned';
                }

                return $this->is_online ? 'online' : 'offline';
            }
        );
    }
}
