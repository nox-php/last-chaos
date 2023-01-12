<?php

namespace Nox\LastChaos\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class DelayedDeletingScope implements Scope
{
    protected array $extensions = [
        'Restore',
        'WithTrashed',
        'WithoutTrashed',
        'OnlyTrashed'
    ];

    public function apply(Builder $builder, Model $model): void
    {
        $builder->where($model->getQualifiedDeletedAtColumn(), '=', 0);
    }

    public function extend(Builder $builder): void
    {
        foreach ($this->extensions as $extension) {
            $this->{"add{$extension}"}($builder);
        }

        $builder->onDelete(function (Builder $builder) {
            $column = $this->getDeletedAtColumn($builder);

            return $builder->update([
                $column => $builder->getModel()->freshTimestampString(),
            ]);
        });
    }

    protected function getDeletedAtColumn(Builder $builder): string
    {
        if (count((array)$builder->getQuery()->joins) > 0) {
            return $builder->getModel()->getQualifiedDeletedAtColumn();
        }

        return $builder->getModel()->getDeletedAtColumn();
    }

    protected function addRestore(Builder $builder): void
    {
        $builder->macro('restore', function (Builder $builder) {
            $builder->withTrashed();

            return $builder->update([$builder->getModel()->getDeletedAtColumn() => 0]);
        });
    }

    protected function addWithTrashed(Builder $builder): void
    {
        $builder->macro('withTrashed', function (Builder $builder, $withTrashed = true) {
            if (!$withTrashed) {
                return $builder->withoutTrashed();
            }

            return $builder->withoutGlobalScope($this);
        });
    }

    protected function addWithoutTrashed(Builder $builder): void
    {
        $builder->macro('withoutTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where(
                $model->getQualifiedDeletedAtColumn(),
                '=',
                0
            );

            return $builder;
        });
    }

    protected function addOnlyTrashed(Builder $builder): void
    {
        $builder->macro('onlyTrashed', function (Builder $builder) {
            $model = $builder->getModel();

            $builder->withoutGlobalScope($this)->where(
                $model->getQualifiedDeletedAtColumn(),
                '!=',
                0
            );

            return $builder;
        });
    }
}