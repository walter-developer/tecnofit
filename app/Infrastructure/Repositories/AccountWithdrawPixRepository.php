<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\AccountWithdrawPix;
use App\Domain\Contracts\AccountWithdrawPixContract;
use App\Infrastructure\Database\Mappers\DomainMapper;
use App\Infrastructure\Database\Models\AccountWithdrawPix as AccountWithdrawPixDb;

class AccountWithdrawPixRepository implements AccountWithdrawPixContract
{
    use DomainMapper;

    public function findById(string $id): ?AccountWithdrawPix
    {
        return $this->toAccountWithdrawPixDomain(AccountWithdrawPixDb::find($id));
    }

    public function save(AccountWithdrawPix $account): AccountWithdrawPix
    {
        return $this->toAccountWithdrawPixDomain(
            AccountWithdrawPixDb::updateOrCreate(
                ['id' => $account->id()],
                [
                    'type' => $account->type(),
                    'key' => $account->key(),
                    'account_withdraw_id' => $account->accountWithdraw()->id(),
                ]
            )
        );
    }
}
