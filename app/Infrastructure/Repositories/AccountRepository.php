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

    public function existsById(string $id): bool
    {
        return DbAccount::where('id', $id)->exists();
    }

    public function findById(string $id): ?Account
    {
        return  $this->toAccountDomain(DbAccount::find($id));
    }

    public function updateBalance(Account $account): Account
    {
        $db = DbAccount::find($account->id());
        $db->update(['balance' => $account->balance()]);
        return $this->toAccountDomain($db);
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
