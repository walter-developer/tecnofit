<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Infrastructure\Database\Models;

use App\Infrastructure\Database\Models\Account;
use App\Infrastructure\Database\Models\AccountWithdraw;
use PHPUnit\Framework\TestCase;
use \Hyperf\Database\Model\Relations\HasMany;

class AccountTest extends TestCase
{
    public function testFillableProperties(): void
    {
        $account = new Account();

        $this->assertEquals(
            ['name', 'balance', 'created_at', 'updated_at', 'deleted_at'],
            $account->getFillable()
        );
    }

    public function testTableName(): void
    {
        $account = new Account();

        $this->assertSame('account', $account->getTable());
    }

    public function testWithdrawsRelation(): void
    {
        $account = new Account();

        $relation = $account->withdraws();

        $this->assertInstanceOf(HasMany::class, $relation);

        $this->assertSame('account_id', $relation->getForeignKeyName());

        $this->assertSame(AccountWithdraw::class, get_class($relation->getRelated()));
    }
}
