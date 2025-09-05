<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\AccountWithdraw;
use App\Domain\Contracts\AccountWithdrawContract;
use App\Infrastructure\Database\Mappers\DomainMapper;
use App\Infrastructure\Database\Models\AccountWithdraw as AccountWithdrawDb;

class AccountWithdrawRepository implements AccountWithdrawContract
{

    use DomainMapper;

    public function findById(string $id): ?AccountWithdraw
    {
        return $this->toAccountWithdrawDomain(AccountWithdrawDb::find($id));
    }

    public function save(AccountWithdraw $accountWithdraw): AccountWithdraw
    {
        return  $this->toAccountWithdrawDomain(
            AccountWithdrawDb::updateOrCreate(
                ['id' => $accountWithdraw->id()],
                [
                    'method' => $accountWithdraw->method(),
                    'amount' => $accountWithdraw->amount(),
                    'scheduled' => $accountWithdraw->scheduled(),
                    'done' => $accountWithdraw->done(),
                    'error' => $accountWithdraw->error(),
                    'errorReason' => $accountWithdraw->errorReason(),
                    'scheduled_for' => $accountWithdraw->schedule(),
                    'account_id' => $accountWithdraw->account()->id(),
                ]
            )
        );
    }
}
