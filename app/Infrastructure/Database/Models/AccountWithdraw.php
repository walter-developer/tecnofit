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

use App\Domain\Enums\MethodEnum;
use App\Infrastructure\Database\Models\Main\Model;

abstract class AccountWithdraw extends Model
{

    protected array $fillable = [
        'id',
        'account_id',
        'method',
        'amount',
        'scheduled',
        'scheduled_for',
        'done',
        'error',
        'error_reason',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected ?string $table = 'account_withdraw';

    protected array $casts = [
        'method' => MethodEnum::class,
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function receiver()
    {
        return $this->hasOne(AccountWithdrawPix::class, 'account_withdraw_id');
    }
}
