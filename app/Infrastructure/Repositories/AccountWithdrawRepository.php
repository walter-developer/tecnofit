<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Entities\AccountWithdraw;
use App\Domain\Contracts\AccountWithdrawContract;
use App\Infrastructure\Database\Mappers\DomainMapper;
use App\Infrastructure\Database\Models\AccountWithdraw as AccountWithdrawDb;
use Carbon\Carbon;
use Closure;

class AccountWithdrawRepository implements AccountWithdrawContract
{

    use DomainMapper;

    public function findById(string $id): ?AccountWithdraw
    {
        return $this->toAccountWithdrawDomain(AccountWithdrawDb::find($id));
    }

    public function chunkScheduledWithdrawals(Closure $chunk, int $limit = 20): void
    {
        $now = Carbon::now();
        AccountWithdrawDb::where('scheduled', true)
            ->where('scheduled_for', '<=', $now)
            ->where('done', false)
            ->where('error', false)
            ->whereNull('error_reason')
            ->chunk($limit, function ($withdrawals) use ($chunk) {
                foreach ($withdrawals as $withdraw) {
                    $chunk($this->toAccountWithdrawDomain($withdraw));
                }
            });
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
                    'error_reason' => $accountWithdraw->errorReason(),
                    'scheduled_for' => $accountWithdraw->schedule(),
                    'account_id' => $accountWithdraw->account()->id(),
                ]
            )
        );
    }
}
