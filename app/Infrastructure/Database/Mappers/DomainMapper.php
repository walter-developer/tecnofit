<?php

namespace App\Infrastructure\Database\Mappers;

use App\Domain\Entities\Account;
use App\Domain\Entities\AccountWithdraw;
use App\Domain\Entities\AccountWithdrawPix;
use App\Infrastructure\Database\Models\Account as AccountDb;
use App\Infrastructure\Database\Models\AccountWithdraw as AccountWithdrawDb;
use App\Infrastructure\Database\Models\AccountWithdrawPix as AccountWithdrawPixDb;

trait DomainMapper
{
    protected function toAccountDomain(AccountDb $db): Account
    {
        return new Account(
            id: $db->id,
            name: $db->name,
            balance: $db->balance,
            createdAt: $db->created_at,
            updatedAt: $db->updated_at,
            deletedAt: $db->deleted_at,
        );
    }

    protected function toAccountWithdrawDomain(AccountWithdrawDb $db): AccountWithdraw
    {
        return new AccountWithdraw(
            id: $db->id,
            account: $this->toAccountDomain($db->account),
            method: $db->method,
            amount: $db->amount,
            scheduled: $db->scheduled,
            done: $db->done,
            error: $db->error,
            errorReason: $db->error_reason,
            schedule: $db->scheduled_for,
            createdAt: $db->created_at,
            updatedAt: $db->updated_at,
            deletedAt: $db->deleted_at,
        );
    }

    protected function toAccountWithdrawPixDomain(AccountWithdrawPixDb $db): AccountWithdrawPix
    {

        $withdrawDomain = $this->toAccountWithdrawDomain($db->withdraw);

        return new AccountWithdrawPix(
            id: $db->id,
            type: $db->type,
            key: $db->key,
            accountWithdraw: $withdrawDomain,
            createdAt: $db->created_at,
            updatedAt: $db->updated_at,
            deletedAt: $db->deleted_at,
        );
    }
}
