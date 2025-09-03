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

class AccountWithdrawPix extends Model
{

    protected $fillable = [
        'id',
        'account_withdraw_id',
        'type',
        'key',
        'type',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $table = 'account_withdraw_pix';
}
