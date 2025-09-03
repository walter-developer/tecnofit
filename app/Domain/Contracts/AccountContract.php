<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Account;

interface AccountContract
{
    public function save(Account $account): Account;

    public function findById(string $id): ?Account;
}
