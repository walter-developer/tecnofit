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

namespace App\Infrastructure\Database\Models;

use App\Infrastructure\Database\Models\Main\Model;

abstract class Account extends Model
{

    protected array $fillable = [
        'id',
        'name',
        'balance',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected ?string $table = 'account';

    public function withdraws()
    {
        return $this->hasMany(AccountWithdraw::class, 'account_id');
    }
}
