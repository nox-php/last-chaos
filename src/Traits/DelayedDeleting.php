<?php

namespace Nox\LastChaos\Traits;

use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

trait DelayedDeleting
{
    public bool $forceDeleting = false;

    public static function bootDelayedDeleting()
    {
        static::addGlobalScope(new SoftDeletingScope());
    }

    public function initializeSoftDeletes()
    {
        if (! isset($this->casts[$this->getDeletedAtColumn()])) {
            $this->casts[$this->getDeletedAtColumn()] = 'datetime';
        }
    }

    public function trashed(): bool
    {
        return $this->{$this->getDeletedAtColumn()} !== 0;
    }

    public function delete()
    {
        if (! $this->exists) {
            return;
        }

        if (! $this->forceDeleting) {
            $this->forceFill([
                $this->getDeletedAtColumn() => now()->addDay()->unix(),
            ])->save();

            return;
        }

        parent::delete();
    }

    public function forceDelete()
    {
        $this->forceDeleting = true;

        $this->delete();
    }

    public function restore()
    {
        if (! $this->exists) {
            return;
        }

        $this->forceFill([
            $this->getDeletedAtColumn() => 0,
        ])->save();
    }

    public function withTrashed(Builder $query): Builder
    {
        return $query;
    }

    public function withoutTrashed(Builder $query): Builder
    {
        return $query->where(
            $this->getDeletedAtColumn(),
            '=',
            0
        );
    }

    public function onlyTrashed(Builder $query): Builder
    {
        return $query->where(
            $this->getDeletedAtColumn(),
            '!=',
            0
        );
    }

    protected function getDeletedAtColumn(): string
    {
        return 'a_deletedelay';
    }

    public function getQualifiedDeletedAtColumn()
    {
        return $this->qualifyColumn($this->getDeletedAtColumn());
    }
}
