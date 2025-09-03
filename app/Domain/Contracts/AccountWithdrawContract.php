<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

use App\Domain\Entities\AccountWithdraw;

interface AccountWithdrawContract
{
    public function save(AccountWithdraw $accountWithdraw): AccountWithdraw;

    public function findById(string $id): ?AccountWithdraw;
}
