<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\Account;
use App\Domain\Entities\AccountWithdraw;
use App\Infrastructure\Database\Models\Account as AccountDb;
use App\Infrastructure\Database\Models\AccountWithdraw as AccountWithdrawDb;

class AccountWithdrawRepository implements AccountWithdrawContract
{
    public function save(AccountWithdraw $accountWithdraw): AccountWithdraw
    {
        return AccountWithdrawDb::updateOrCreate(
            ['id' => $accountWithdraw->id()],
            [
                'method' => $accountWithdraw->method(),
                'amount' => $accountWithdraw->amount(),
                'scheduled' => $accountWithdraw->scheduled(),
                'done' => $accountWithdraw->done(),
                'error' => $accountWithdraw->error(),
                'errorReason' => $accountWithdraw->errorReason(),
                'scheduledFor' => $accountWithdraw->scheduledFor(),
                'account_id' => $accountWithdraw->account()->id(),
            ]
        );
    }

    public function findById(string $id): ?AccountWithdraw
    {
        $accountWithdrawDb = AccountWithdrawDb::find($id);
        $dbAccount = $accountWithdrawDb?->account;

        return $this->toAccountWithdrawDomain(
            $accountWithdrawDb,
            $this->toAccountDomain($dbAccount)
        );
    }

    private function toAccountDomain(
        accountDb $accountDb
    ): Account {
        return new Account(
            id: $accountDb->id,
            name: $accountDb->name,
            balance: $accountDb->balance,
            createdAt: $accountDb->created_at,
            updatedAt: $accountDb->updated_at,
            deletedAt: $accountDb->deleted_at,
        );
    }

    private function toAccountWithdrawDomain(
        AccountWithdrawDb $accountWithdrawDb,
        Account $accountDomain
    ): AccountWithdraw {
        return new AccountWithdraw(
            id: $accountWithdrawDb->id,
            account: $accountDomain,
            method: $accountWithdrawDb->method,
            amount: $accountWithdrawDb->amount,
            scheduled: $accountWithdrawDb->scheduled,
            done: $accountWithdrawDb->done,
            error: $accountWithdrawDb->error,
            errorReason: $accountWithdrawDb->errorReason,
            scheduledFor: $accountWithdrawDb->scheduledFor,
            createdAt: $accountWithdrawDb->created_at,
            updatedAt: $accountWithdrawDb->updated_at,
            deletedAt: $accountWithdrawDb->deleted_at,
        );
    }
}
