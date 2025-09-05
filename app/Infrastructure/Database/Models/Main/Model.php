<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Infrastructure\Database\Models\Main;

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
}
