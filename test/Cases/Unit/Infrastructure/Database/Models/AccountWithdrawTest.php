<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Infrastructure\Database\Models;

use App\Domain\Enums\MethodEnum;
use App\Infrastructure\Database\Models\Account;
use App\Infrastructure\Database\Models\AccountWithdraw;
use App\Infrastructure\Database\Models\AccountWithdrawPix;
use Hyperf\Database\Model\Relations\BelongsTo;
use Hyperf\Database\Model\Relations\HasOne;
use PHPUnit\Framework\TestCase;

class AccountWithdrawTest extends TestCase
{
    public function testFillableProperties(): void
    {
        $withdraw = new AccountWithdraw();

        $this->assertEquals(
            [
                'account_id',
                'method',
                'amount',
                'scheduled',
                'scheduled_for',
                'done',
                'error',
                'error_reason',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $withdraw->getFillable()
        );
    }

    public function testTableName(): void
    {
        $withdraw = new AccountWithdraw();

        $this->assertSame('account_withdraw', $withdraw->getTable());
    }

    public function testCasts(): void
    {
        $withdraw = new AccountWithdraw();

        $casts = $withdraw->getCasts();

        $this->assertArrayHasKey('method', $casts);
        $this->assertSame(MethodEnum::class, $casts['method']);

        $this->assertArrayHasKey('scheduled_for', $casts);
        $this->assertSame('datetime', $casts['scheduled_for']);
    }

    public function testAccountRelation(): void
    {
        $withdraw = new AccountWithdraw();

        $relation = $withdraw->account();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('account_id', $relation->getForeignKeyName());
        $this->assertSame(Account::class, get_class($relation->getRelated()));
    }

    public function testReceiverRelation(): void
    {
        $withdraw = new AccountWithdraw();

        $relation = $withdraw->receiver();

        $this->assertInstanceOf(HasOne::class, $relation);
        $this->assertSame('account_withdraw_id', $relation->getForeignKeyName());
        $this->assertSame(AccountWithdrawPix::class, get_class($relation->getRelated()));
    }
}
