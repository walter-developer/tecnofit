<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Account;
use App\Infrastructure\Database\Models\Account as DbAccount;

class AccountRepository implements AccountContract
{
    public function save(Account $account): Account
    {
        return DbAccount::updateOrCreate(
            ['id' => $account->id()],
            [
                'name' => $account->name(),
                'balance' => $account->balance(),
            ]
        );
    }

    public function findById(string $id): ?Account
    {
        $dbAccount = DbAccount::find($id);

        return $dbAccount ? new Account(
            id: $dbAccount->id,
            name: $dbAccount->name,
            balance: $dbAccount->balance,
        ) : null;
    }
}
