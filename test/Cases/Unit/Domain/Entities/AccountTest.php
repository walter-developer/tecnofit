<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Domain\Entities;

use App\Domain\Entities\Account;
use PHPUnit\Framework\TestCase;

class AccountTest extends TestCase
{
    /** @test */
    public function itCanBeConstructedWithAllProperties(): void
    {
        $createdAt = new \DateTime('2025-01-01 12:00:00');
        $updatedAt = new \DateTime('2025-01-02 12:00:00');
        $deletedAt = new \DateTime('2025-01-03 12:00:00');

        $account = new Account(
            balance: 100.0,
            name: 'Walter',
            id: 'acc123',
            createdAt: $createdAt,
            updatedAt: $updatedAt,
            deletedAt: $deletedAt
        );

        $this->assertSame('acc123', $account->id());
        $this->assertSame('Walter', $account->name());
        $this->assertSame(100.0, $account->balance());
        $this->assertSame($createdAt, $account->createdAt());
        $this->assertSame($updatedAt, $account->updatedAt());
        $this->assertSame($deletedAt, $account->deletedAt());
    }

    /** @test */
    public function itCanBeConstructedWithDefaults(): void
    {
        $account = new Account();

        $this->assertNull($account->id());
        $this->assertNull($account->name());
        $this->assertSame(0.0, $account->balance());
        $this->assertNull($account->createdAt());
        $this->assertNull($account->updatedAt());
        $this->assertNull($account->deletedAt());
    }
}
