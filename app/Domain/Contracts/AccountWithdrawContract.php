<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

use App\Domain\Entities\AccountWithdraw;
use Closure;

interface AccountWithdrawContract
{
    public function save(AccountWithdraw $accountWithdraw): AccountWithdraw;

    public function findById(string $id): ?AccountWithdraw;

    public function chunkScheduledWithdrawals(Closure $chunk, int $limit = 20): void;
}
