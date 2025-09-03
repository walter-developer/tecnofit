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

class Model extends HyperfModel
{

    use HasUuids;

    public bool $incrementing = true;

    protected string $keyType = 'string';
}
