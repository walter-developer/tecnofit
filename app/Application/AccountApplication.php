<?php

namespace App\Application;

use App\Domain\Enums\MethodEnum;
use App\Domain\Enums\TypeKeyEnum;
use App\Domain\Contracts\AccountContract;
use App\Domain\Contracts\AccountWithdrawContract;
use App\Domain\Contracts\AccountWithdrawPixContract;
use DateTime;

class AccountApplication
{
    public function __construct(
        protected AccountContract $accountRepository,
        protected AccountWithdrawContract $accountWithdrawRepository,
        protected AccountWithdrawPixContract $accountWithdrawPixRepository
    ) {}

    public function createAccount(array $account): array
    {
        $account = $this->accountRepository->save(
            new \App\Domain\Entities\Account(
                name: $account['name'],
                balance: 0
            )
        );

        return [
            'key' => $account->id(),
            'name' => $account->name(),
            'balance' => $account->balance(),
        ];
    }

    public function loadBalance(array $account): array
    {
        $account = $this->accountRepository->save(
            new \App\Domain\Entities\Account(
                id: $account['id'],
                name: $account['name'],
                balance: $account['balance']
            )
        );

        return [
            'key' => $account->id(),
            'name' => $account->name(),
            'balance' => $account->balance(),
        ];
    }

    public function withdraw(array $transaction): array
    {

        $withdraw =  $this->accountWithdrawRepository->save(
            new \App\Domain\Entities\AccountWithdraw(
                amount: floatval($transaction['amount']),
                method: MethodEnum::from($transaction['method']),
                account: $this->accountRepository->findById($transaction['account_id']),
                scheduled: boolval($transaction['scheduled']),
                schedule: DateTime::createFromFormat('Y-m-d H:i:s', $transaction['schedule']),
            )
        );

        $withdrawPix =  $this->accountWithdrawPixRepository->save(
            new \App\Domain\Entities\AccountWithdrawPix(
                accountWithdraw: $withdraw,
                key: $transaction['key'],
                type: TypeKeyEnum::key($transaction['key']),
            )
        );

        return [
            'key' => $withdrawPix->key(),
            'type' => $withdrawPix->type()->value,
            'amount' => $withdraw->amount(),
            'method' => $withdraw->method(),
            'schedule' => $withdraw->schedule()?->format('Y-m-d H:i:s'),
        ];
    }
}
