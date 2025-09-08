<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Domain\Entities;

use App\Domain\Entities\AccountWithdraw;
use App\Domain\Entities\AccountWithdrawPix;
use App\Domain\Enums\TypeKeyEnum;
use App\Domain\Enums\MethodEnum;
use App\Domain\Entities\Account;
use PHPUnit\Framework\TestCase;

class AccountWithdrawPixTest extends TestCase
{
    /** @test */
    public function itCanBeConstructedWithAllProperties(): void
    {
        $createdAt = new \DateTime('2025-01-01 12:00:00');
        $updatedAt = new \DateTime('2025-01-02 12:00:00');
        $deletedAt = new \DateTime('2025-01-03 12:00:00');

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
            error: false,
            scheduled: true
        );

        $withdrawPix = new AccountWithdrawPix(
            key: '123456',
            type: TypeKeyEnum::CPF,
            accountWithdraw: $withdraw,
            id: 'pix123',
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt
        );

        $this->assertSame('pix123', $withdrawPix->id());
        $this->assertSame('123456', $withdrawPix->key());
        $this->assertSame(TypeKeyEnum::CPF, $withdrawPix->type());
        $this->assertSame($withdraw, $withdrawPix->accountWithdraw());
        $this->assertSame($createdAt, $withdrawPix->createdAt());
        $this->assertSame($updatedAt, $withdrawPix->updatedAt());
        $this->assertSame($deletedAt, $withdrawPix->deletedAt());
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

        $withdrawPix = new AccountWithdrawPix(
            key: '000000',
            type: TypeKeyEnum::CPF,
            accountWithdraw: $withdraw
        );

        $this->assertNull($withdrawPix->id());
        $this->assertSame('000000', $withdrawPix->key());
        $this->assertSame(TypeKeyEnum::CPF, $withdrawPix->type());
        $this->assertSame($withdraw, $withdrawPix->accountWithdraw());
        $this->assertNull($withdrawPix->createdAt());
        $this->assertNull($withdrawPix->updatedAt());
        $this->assertNull($withdrawPix->deletedAt());
    }
}
