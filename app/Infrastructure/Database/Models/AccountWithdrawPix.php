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

use App\Domain\Enums\TypeKeyEnum;
use App\Infrastructure\Database\Models\Main\Model;

class AccountWithdrawPix extends Model
{

    protected array $fillable = [
        'id',
        'account_withdraw_id',
        'key',
        'type',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected ?string $table = 'account_withdraw_pix';

    protected array $casts = [
        'type' => TypeKeyEnum::class,
    ];

    public function withdraw()
    {
        return $this->belongsTo(AccountWithdraw::class, 'account_id');
    }
}
