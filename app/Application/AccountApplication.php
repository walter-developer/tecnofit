<?php

namespace App\Application;

use App\Domain\Enums\MethodEnum;
use App\Domain\Enums\TypeKeyEnum;
use App\Domain\Contracts\AccountContract;
use App\Domain\Contracts\AccountWithdrawContract;
use App\Domain\Contracts\AccountWithdrawPixContract;
use App\Domain\Entities\Account;
use App\Domain\Entities\AccountWithdraw;
use App\Domain\Entities\AccountWithdrawPix;
use App\Domain\Exception\InsufficientBalanceException;
use App\Domain\Exception\InvalidAccountException;
use DateTime;
use Throwable;

class AccountApplication
{
    public function __construct(
        protected AccountContract $accountRepository,
        protected AccountWithdrawContract $accountWithdrawRepository,
        protected AccountWithdrawPixContract $accountWithdrawPixRepository
    ) {}

    private function validateExisitsAccount(string $accountId): void
    {
        //Validação de conta existente
        if (!$this->accountRepository->existsById($accountId)) {
            throw new InvalidAccountException();
        }
    }

    private function validateSufficientBalance(Account $account, float $withdrawAmount): void
    {
        //Validação de saldo suficiente
        if ($account->balance() < $withdrawAmount) {
            throw new InsufficientBalanceException(
                $account->balance(),
                $withdrawAmount
            );
        }
    }

    public function processWithdrawal(AccountWithdraw $accountWithdraw): void
    {
        try {
            //Processamento agendado
            $this->tryProcessWithdrawal($accountWithdraw);
        } catch (Throwable $e) {
            //Callback falha de processamento agendado
            $this->failProcessWithdrawal($accountWithdraw, $e);
        }
    }

    public function createAccount(array $account): array
    {
        //Gera uma nova conta para saque
        $account = $this->accountRepository->save(
            new Account(
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

    public function updateBalance(array $account): array
    {
        //validação de conta existente
        $this->validateExisitsAccount($account['id']);

        $account = $this->accountRepository->updateBalance(
            new Account(
                id: $account['id'],
                balance: $account['balance']
            )
        );

        return [
            'key' => $account->id(),
            'balance' => $account->balance(),
        ];
    }

    public function createWithdraw(array $transaction): array
    {

        //validação de conta existente
        $this->validateExisitsAccount($transaction['account_id']);

        $withdrawAmount = floatval($transaction['amount']);
        $account =  $this->accountRepository->findById($transaction['account_id']);

        //Validação de saldo suficiente para operação
        $this->validateSufficientBalance($account, $withdrawAmount);

        //Saldo debitado antecipadamente para saques agendados
        //Saldo debitado antecipadamente para saques instantâneos
        $account = $this->accountRepository->updateBalance(
            new Account(
                id: $account->id(),
                balance: $account->balance() - $withdrawAmount
            )
        );

        //Agendamento e ou pagamento instantaneo do saque
        $withdraw =  $this->accountWithdrawRepository->save(
            new AccountWithdraw(
                account: $account,
                amount: $withdrawAmount,
                done: !boolval($transaction['schedule']),
                method: MethodEnum::from(strtoupper($transaction['method'])),
                scheduled: boolval($transaction['schedule']),
                schedule: boolval($transaction['schedule'])
                    ? DateTime::createFromFormat('Y-m-d H:i', $transaction['schedule']) : null,
            )
        );

        //Registros das informações de pagamento
        $withdrawPix =  $this->accountWithdrawPixRepository->save(
            new AccountWithdrawPix(
                accountWithdraw: $withdraw,
                key: $transaction['pix']['key'],
                type: TypeKeyEnum::key(strtoupper($transaction['pix']['key'])),
            )
        );

        return [
            'key' => $withdrawPix->key(),
            'type' => strtolower($withdrawPix->type()->value),
            'amount' => $withdraw->amount(),
            'method' => strtolower($withdraw->method()->value),
            'schedule' => $withdraw->schedule()?->format('Y-m-d H:i'),
        ];
    }


    private function tryProcessWithdrawal(AccountWithdraw $accountWithdraw): void
    {
        //Tenta processamento agendado
        $account =  $accountWithdraw->account();

        $this->accountWithdrawRepository->save(
            new AccountWithdraw(
                done: true,
                account: $account,
                amount: $accountWithdraw->amount(),
                method: $accountWithdraw->method(),
                scheduled: $accountWithdraw->scheduled(),
                schedule: $accountWithdraw->schedule()
            )
        );
    }

    private function failProcessWithdrawal(AccountWithdraw $accountWithdraw, Throwable $exception): void
    {
        //Callback de processamento agendado com falha
        $this->accountWithdrawRepository->save(
            new AccountWithdraw(
                error: true,
                account: $accountWithdraw->account(),
                errorReason: $exception->getMessage(),
                done: $accountWithdraw->done(),
                amount: $accountWithdraw->amount(),
                method: $accountWithdraw->method(),
                scheduled: $accountWithdraw->scheduled(),
                schedule: $accountWithdraw->schedule()
            )
        );
    }
}
