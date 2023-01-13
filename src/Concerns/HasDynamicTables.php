<?php

namespace Nox\LastChaos\Concerns;

use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasDynamicTables
{
    protected function hasManyDynamic(
        string $related,
        string $table,
        ?string $foreignKey = null,
        ?string $localKey = null
    ): HasMany
    {
        $instance = $this->newRelatedInstance($related)
            ->setTable($table);

        $foreignKey = $foreignKey ?: $this->getForeignKey();

        $localKey = $localKey ?: $this->getKeyName();

        return $this->newHasMany(
            $instance->newQuery(), $this, $instance->getTable() . '.' . $foreignKey, $localKey
        );
    }
}