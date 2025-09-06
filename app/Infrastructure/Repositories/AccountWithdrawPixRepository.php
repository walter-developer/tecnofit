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

    public function __construct(private AccountWithdrawPixDb $accountWithdraw) {}

    public function findById(string $id): ?AccountWithdrawPix
    {
        return $this->toAccountWithdrawPixDomain($this->accountWithdraw->find($id));
    }

    public function findWithdrawById(string $id): ?AccountWithdrawPix
    {
        return $this->toAccountWithdrawPixDomain(
            $this->accountWithdraw->where('account_withdraw_id', $id)->first()
        );
    }

    public function save(AccountWithdrawPix $account): AccountWithdrawPix
    {
        return $this->toAccountWithdrawPixDomain(
            $this->accountWithdraw->updateOrInsert(
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
