<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Infrastructure\Repositories;

use App\Domain\Entities\Account;
use App\Domain\Entities\AccountWithdraw;
use App\Domain\Enums\MethodEnum;
use App\Infrastructure\Database\Models\Account as DbAccount;
use App\Infrastructure\Database\Models\AccountWithdraw as DbAccountWithdraw;
use App\Infrastructure\Repositories\AccountWithdrawRepository;
use PHPUnit\Framework\TestCase;
use Mockery;
use DateTime;
use Carbon\Carbon;

class AccountWithdrawRepositoryTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testFindById(): void
    {
        $dbAccount = new DbAccount();
        $dbAccount->id = 'acc123';
        $dbAccount->name = 'Walter';
        $dbAccount->balance = 100.0;
        $dbAccount->created_at = new DateTime('2025-01-01 12:00:00');
        $dbAccount->updated_at = new DateTime('2025-01-01 12:00:00');

        $dbWithdraw = new DbAccountWithdraw();
        $dbWithdraw->id = 'with123';
        $dbWithdraw->account_id = 'acc123';
        $dbWithdraw->method = 'PIX';
        $dbWithdraw->amount = 50.0;
        $dbWithdraw->scheduled = true;
        $dbWithdraw->done = false;
        $dbWithdraw->error = false;
        $dbWithdraw->error_reason = null;
        $dbWithdraw->scheduled_for = new DateTime('2025-01-01 12:00:00');
        $dbWithdraw->created_at = new DateTime('2025-01-01 12:00:00');
        $dbWithdraw->updated_at = new DateTime('2025-01-01 12:00:00');
        $dbWithdraw->setRelation('account', $dbAccount);

        $mock = Mockery::mock(DbAccountWithdraw::class);
        $mock->shouldReceive('find')->with('with123')->andReturn($dbWithdraw);

        $repository = new AccountWithdrawRepository($mock);
        $withdraw = $repository->findById('with123');

        $this->assertInstanceOf(AccountWithdraw::class, $withdraw);
        $this->assertSame('with123', $withdraw->id());
        $this->assertSame('PIX', $withdraw->method()->value);
        $this->assertSame(50.0, $withdraw->amount());
        $this->assertSame('acc123', $withdraw->account()->id());
    }

    public function testSave(): void
    {
        $dbAccount = new DbAccount();
        $dbAccount->id = 'acc123';
        $dbAccount->name = 'Walter';
        $dbAccount->balance = 100.0;

        $dbWithdraw = new DbAccountWithdraw();
        $dbWithdraw->id = 'with123';
        $dbWithdraw->account_id = 'acc123';
        $dbWithdraw->method = 'PIX';
        $dbWithdraw->amount = 50.0;
        $dbWithdraw->scheduled = true;
        $dbWithdraw->done = false;
        $dbWithdraw->error = false;
        $dbWithdraw->error_reason = null;
        $dbWithdraw->scheduled_for = new DateTime('2025-01-01 12:00:00');
        $dbWithdraw->setRelation('account', $dbAccount);

        $mock = Mockery::mock(DbAccountWithdraw::class);
        $mock->shouldReceive('updateOrInsert')
            ->withAnyArgs()
            ->andReturn($dbWithdraw);

        $repository = new AccountWithdrawRepository($mock);

        $accountDomain = new Account(
            id: 'acc123',
            name: 'Walter',
            balance: 100.0
        );

        $withdrawDomain = new AccountWithdraw(
            id: 'with123',
            method: MethodEnum::PIX,
            amount: 50.0,
            account: $accountDomain
        );

        $saved = $repository->save($withdrawDomain);

        $this->assertInstanceOf(AccountWithdraw::class, $saved);
        $this->assertSame('with123', $saved->id());
        $this->assertSame('PIX', $saved->method()->value);
        $this->assertSame(50.0, $saved->amount());
        $this->assertSame('acc123', $saved->account()->id());
    }
}
