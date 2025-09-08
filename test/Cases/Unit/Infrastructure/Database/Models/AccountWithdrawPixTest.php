<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Infrastructure\Database\Models;

use App\Domain\Enums\TypeKeyEnum;
use App\Infrastructure\Database\Models\AccountWithdraw;
use App\Infrastructure\Database\Models\AccountWithdrawPix;
use Hyperf\Database\Model\Relations\BelongsTo;
use PHPUnit\Framework\TestCase;

class AccountWithdrawPixTest extends TestCase
{
    public function testFillableProperties(): void
    {
        $pix = new AccountWithdrawPix();

        $this->assertEquals(
            [
                'account_withdraw_id',
                'key',
                'type',
                'created_at',
                'updated_at',
                'deleted_at'
            ],
            $pix->getFillable()
        );
    }

    public function testTableName(): void
    {
        $pix = new AccountWithdrawPix();

        $this->assertSame('account_withdraw_pix', $pix->getTable());
    }

    public function testCasts(): void
    {
        $pix = new AccountWithdrawPix();

        $casts = $pix->getCasts();

        $this->assertArrayHasKey('type', $casts);
        $this->assertSame(TypeKeyEnum::class, $casts['type']);
    }

    public function testWithdrawRelation(): void
    {
        $pix = new AccountWithdrawPix();

        $relation = $pix->withdraw();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertSame('account_withdraw_id', $relation->getForeignKeyName());
        $this->assertSame(AccountWithdraw::class, get_class($relation->getRelated()));
    }
}
