<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models\Main;

use Hyperf\Collection\Collection;
use Hyperf\Database\Model\Concerns\HasUuids;
use Hyperf\Database\Model\Model as HyperfModel;
use Hyperf\Database\Model\Builder;

class Model extends HyperfModel
{
    use HasUuids;

    public bool $incrementing = false;

    protected string $keyType = 'string';

    protected function performInsert(Builder $query)
    {
        $key = $this->getKeyName();
        $this->{$key} = $this->newUniqueId();
        return parent::performInsert($query);
    }

    public function updateOrInsert(array $attributes, array $values): Model
    {
        return static::updateOrCreate($attributes, $values);
    }
}
