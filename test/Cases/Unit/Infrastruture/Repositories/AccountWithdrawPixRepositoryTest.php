<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Infrastructure\Repositories;

use App\Domain\Entities\Account;
use App\Domain\Entities\AccountWithdraw;
use PHPUnit\Framework\TestCase;
use App\Infrastructure\Repositories\AccountWithdrawPixRepository;
use App\Domain\Entities\AccountWithdrawPix;
use App\Domain\Enums\MethodEnum;
use App\Domain\Enums\TypeKeyEnum;
use App\Infrastructure\Database\Models\Account as DbAccount;
use App\Infrastructure\Database\Models\AccountWithdraw as DbAccountWithdraw;
use App\Infrastructure\Database\Models\AccountWithdrawPix as AccountWithdrawPixDb;
use Mockery;
use DateTime;

class AccountWithdrawPixRepositoryTest extends TestCase
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
        $dbAccount->deleted_at = null;

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
        $dbWithdraw->deleted_at = null;
        $dbWithdraw->setRelation('account', $dbAccount);

        $dbPixRow = new AccountWithdrawPixDb();
        $dbPixRow->id = 'pix123';
        $dbPixRow->type = TypeKeyEnum::EMAIL;
        $dbPixRow->key = 'test@test.com';
        $dbPixRow->account_withdraw_id = 'with123';
        $dbPixRow->created_at = new DateTime('2025-01-01 12:00:00');
        $dbPixRow->updated_at = new DateTime('2025-01-01 12:00:00');
        $dbPixRow->setRelation('withdraw', $dbWithdraw);

        $mock = Mockery::mock(AccountWithdrawPixDb::class);
        $mock->shouldReceive('find')->with('pix123')->andReturn($dbPixRow);

        $repository = new AccountWithdrawPixRepository($mock);
        $pix = $repository->findById('pix123');

        $this->assertInstanceOf(AccountWithdrawPix::class, $pix);
        $this->assertSame('pix123', $pix->id());
        $this->assertSame('EMAIL', $pix->type()->value);
        $this->assertSame('test@test.com', $pix->key());
        $this->assertSame('with123', $pix->accountWithdraw()->id());
    }

    public function testFindWithdrawById(): void
    {
        $dbAccount = new DbAccount();
        $dbAccount->id = 'acc123';
        $dbAccount->name = 'Walter';
        $dbAccount->balance = 100.0;
        $dbAccount->created_at = new DateTime('2025-01-01 12:00:00');
        $dbAccount->updated_at = new DateTime('2025-01-01 12:00:00');
        $dbAccount->deleted_at = null;

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
        $dbWithdraw->deleted_at = null;
        $dbWithdraw->setRelation('account', $dbAccount);

        $dbPixRow = new AccountWithdrawPixDb();
        $dbPixRow->id = 'pix123';
        $dbPixRow->type = TypeKeyEnum::EMAIL;
        $dbPixRow->key = 'test@test.com';
        $dbPixRow->account_withdraw_id = 'with123';
        $dbPixRow->created_at = new DateTime('2025-01-01 12:00:00');
        $dbPixRow->updated_at = new DateTime('2025-01-01 12:00:00');
        $dbPixRow->setRelation('withdraw', $dbWithdraw);

        $mock = Mockery::mock(AccountWithdrawPixDb::class);
        $mock->shouldReceive('where')
            ->with('account_withdraw_id', 'with123')
            ->andReturnSelf();
        $mock->shouldReceive('first')->andReturn($dbPixRow);

        $repository = new AccountWithdrawPixRepository($mock);
        $pix = $repository->findWithdrawById('with123');

        $this->assertInstanceOf(AccountWithdrawPix::class, $pix);
        $this->assertSame('pix123', $pix->id());
        $this->assertSame('EMAIL', $pix->type()->value);
        $this->assertSame('test@test.com', $pix->key());
        $this->assertSame('with123', $pix->accountWithdraw()->id());
    }

    public function testSave(): void
    {
        $dbAccount = new DbAccount();
        $dbAccount->id = 'acc123';
        $dbAccount->name = 'Walter';
        $dbAccount->balance = 100.0;
        $dbAccount->created_at = new DateTime('2025-01-01 12:00:00');
        $dbAccount->updated_at = new DateTime('2025-01-01 12:00:00');
        $dbAccount->deleted_at = null;

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
        $dbWithdraw->deleted_at = null;
        $dbWithdraw->setRelation('account', $dbAccount);

        $dbPixRow = new AccountWithdrawPixDb();
        $dbPixRow->id = 'pix123';
        $dbPixRow->type = TypeKeyEnum::EMAIL;
        $dbPixRow->key = 'test@test.com';
        $dbPixRow->account_withdraw_id = 'with123';
        $dbPixRow->created_at = new DateTime('2025-01-01 12:00:00');
        $dbPixRow->updated_at = new DateTime('2025-01-01 12:00:00');
        $dbPixRow->setRelation('withdraw', $dbWithdraw);

        $mock = Mockery::mock(AccountWithdrawPixDb::class)->makePartial();
        $mock->shouldReceive('updateOrInsert')
            ->with([
                'id' => 'pix123'
            ], [
                'type' => TypeKeyEnum::EMAIL,
                'key' => 'test@test.com',
                'account_withdraw_id' => 'with123',
            ])
            ->andReturn($dbPixRow);

        $repository = new AccountWithdrawPixRepository($mock);

        $accountDomain = new Account(
            id: 'acc123',
            name: 'Walter',
            balance: 100.0,
        );

        $withdrawDomain = new AccountWithdraw(
            id: 'with123',
            method: MethodEnum::PIX,
            amount: 50.0,
            account: $accountDomain
        );

        $pixDomain = new AccountWithdrawPix(
            id: 'pix123',
            type: TypeKeyEnum::EMAIL,
            key: 'test@test.com',
            accountWithdraw: $withdrawDomain
        );

        $saved = $repository->save($pixDomain);

        $this->assertInstanceOf(AccountWithdrawPix::class, $saved);
        $this->assertSame('pix123', $saved->id());
        $this->assertSame('EMAIL', $saved->type()->value);
        $this->assertSame('test@test.com', $saved->key());
        $this->assertSame('with123', $saved->accountWithdraw()->id());
    }
}
