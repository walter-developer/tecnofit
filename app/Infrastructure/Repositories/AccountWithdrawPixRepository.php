<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\AccountWithdraw;
use App\Domain\Entities\AccountWithdrawPix;
use App\Infrastructure\Database\Models\AccountWithdraw as AccountWithdrawDb;
use App\Infrastructure\Database\Models\AccountWithdrawPix as AccountWithdrawPixDb;

class AccountWithdrawPixRepository implements AccountWithdrawPixContract
{

    public function save(AccountWithdrawPix $account): AccountWithdrawPix
    {
        return accountWithdrawPixDb::updateOrCreate(
            ['id' => $account->id()],
            [
                'type' => $account->type(),
                'key' => $account->key(),
                'account_withdraw_id' => $account->accountWithdraw()->id(),
            ]
        );
    }

    public function findById(string $id): ?AccountWithdrawPix
    {
        $accountWithdrawPixDb = AccountWithdrawPixDb::find($id);
        $accountWithdrawDb = $accountWithdrawPixDb?->accountWithdraw;

        return $this->toAccountWithdrawPixDomain(
            $accountWithdrawPixDb,
            $this->toAccountWithdrawDomain($accountWithdrawDb)
        );
    }

    private function toAccountWithdrawPixDomain(
        accountWithdrawPixDb $accountWithdrawPixDb,
        AccountWithdraw $domainAccountWithdrawPix
    ): AccountWithdrawPix {
        return new AccountWithdrawPix(
            id: $accountWithdrawPixDb->id,
            type: $accountWithdrawPixDb->name,
            key: $accountWithdrawPixDb->name,
            accountWithdraw: $domainAccountWithdrawPix,
            createdAt: $accountWithdrawPixDb->created_at,
            updatedAt: $accountWithdrawPixDb->updated_at,
            deletedAt: $accountWithdrawPixDb->deleted_at,
        );
    }

    private function toAccountWithdrawDomain(
        AccountWithdrawDb $accountWithdrawDb
    ): AccountWithdraw {
        return new AccountWithdraw(
            id: $accountWithdrawDb->id,
            account: $accountWithdrawDb->account,
            method: $accountWithdrawDb->name,
            amount: $accountWithdrawDb->name,
            scheduled: $accountWithdrawDb->name,
            done: $accountWithdrawDb->done,
            error: $accountWithdrawDb->error,
            errorReason: $accountWithdrawDb->error,
            scheduledFor: $accountWithdrawDb->error,
            createdAt: $accountWithdrawDb->created_at,
            updatedAt: $accountWithdrawDb->updated_at,
            deletedAt: $accountWithdrawDb->deleted_at,
        );
    }
}
