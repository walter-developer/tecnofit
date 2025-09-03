<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\Account;
use App\Domain\Contracts\AccountContract;
use App\Infrastructure\Database\Mappers\DomainMapper;
use App\Infrastructure\Database\Models\Account as DbAccount;

class AccountRepository implements AccountContract
{

    use DomainMapper;

    public function findById(string $id): ?Account
    {
        return  $this->toAccountDomain(DbAccount::find($id));
    }

    public function save(Account $account): Account
    {
        return $this->toAccountDomain(DbAccount::updateOrCreate(
            ['id' => $account->id()],
            [
                'name' => $account->name(),
                'balance' => $account->balance(),
            ]
        ));
    }
}
