<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Domain\Entities;

use App\Domain\Entities\Account;
use App\Domain\Entities\AccountWithdraw;
use App\Domain\Enums\MethodEnum;
use PHPUnit\Framework\TestCase;

class AccountWithdrawTest extends TestCase
{
    /** @test */
    public function itCanBeConstructedWithAllProperties(): void
    {
        $createdAt = new \DateTime('2025-01-01 12:00:00');
        $updatedAt = new \DateTime('2025-01-02 12:00:00');
        $deletedAt = new \DateTime('2025-01-03 12:00:00');
        $schedule = new \DateTime('2025-01-04 12:00:00');

        $account = new Account(
            id: 'acc123',
            name: 'Walter',
            balance: 100.0,
            createdAt: $createdAt,
            updatedAt: $updatedAt
        );

        $withdraw = new AccountWithdraw(
            method: MethodEnum::PIX,
            amount: 50.0,
            account: $account,
            done: true,
            error: true,
            scheduled: true,
            errorReason: 'Erro teste',
            schedule: $schedule,
            id: 'with123',
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt
        );

        $this->assertSame('with123', $withdraw->id());
        $this->assertSame(MethodEnum::PIX, $withdraw->method());
        $this->assertSame(50.0, $withdraw->amount());
        $this->assertTrue($withdraw->done());
        $this->assertTrue($withdraw->error());
        $this->assertTrue($withdraw->scheduled());
        $this->assertSame('Erro teste', $withdraw->errorReason());
        $this->assertSame($schedule, $withdraw->schedule());
        $this->assertSame($account, $withdraw->account());
        $this->assertSame($createdAt, $withdraw->createdAt());
        $this->assertSame($updatedAt, $withdraw->updatedAt());
        $this->assertSame($deletedAt, $withdraw->deletedAt());
    }

    /** @test */
    public function itCanBeConstructedWithDefaults(): void
    {
        $account = new Account();

        $withdraw = new AccountWithdraw(
            method: MethodEnum::PIX,
            amount: 0.0,
            account: $account
        );

        $this->assertNull($withdraw->id());
        $this->assertSame(MethodEnum::PIX, $withdraw->method());
        $this->assertSame(0.0, $withdraw->amount());
        $this->assertFalse($withdraw->done());
        $this->assertFalse($withdraw->error());
        $this->assertFalse($withdraw->scheduled());
        $this->assertNull($withdraw->errorReason());
        $this->assertNull($withdraw->schedule());
        $this->assertSame($account, $withdraw->account());
        $this->assertNull($withdraw->createdAt());
        $this->assertNull($withdraw->updatedAt());
        $this->assertNull($withdraw->deletedAt());
    }
}
