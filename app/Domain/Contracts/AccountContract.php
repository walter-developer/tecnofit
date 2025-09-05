<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

use App\Domain\Entities\Account;

interface AccountContract
{
    public function existsById(string $id): bool;

    public function findById(string $id): ?Account;

    public function save(Account $account): Account;

    public function updateBalance(Account $account): Account;
}
