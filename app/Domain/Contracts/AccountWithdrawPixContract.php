<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

use App\Domain\Entities\AccountWithdrawPix;

interface AccountWithdrawPixContract
{
    public function save(AccountWithdrawPix $accountWithdraw): AccountWithdrawPix;

    public function findById(string $id): ?AccountWithdrawPix;
}
