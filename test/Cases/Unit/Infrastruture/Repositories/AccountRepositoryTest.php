<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Infrastructure\Repositories;

use PHPUnit\Framework\TestCase;
use App\Infrastructure\Repositories\AccountRepository;
use App\Domain\Entities\Account;
use App\Infrastructure\Database\Models\Account as DbAccount;
use Mockery;
use DateTime;

class AccountRepositoryTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function testExistsById(): void
    {
        $dbAccount = Mockery::mock(DbAccount::class)->makePartial();
        $dbAccount->shouldReceive('where')->with('id', 'acc123')->andReturnSelf();
        $dbAccount->shouldReceive('exists')->andReturn(true);

        $repository = new AccountRepository($dbAccount);
        $this->assertTrue($repository->existsById('acc123'));
    }

    /** @test */
    public function testFindById(): void
    {
        $dbAccount = Mockery::mock(DbAccount::class)->makePartial();
        $dbAccount->id = 'acc123';
        $dbAccount->name = 'Walter';
        $dbAccount->balance = 100.0;
        $dbAccount->created_at = new DateTime('2025-01-01 12:00:00');
        $dbAccount->updated_at = new DateTime('2025-01-01 12:00:00');
        $dbAccount->deleted_at = null;

        $dbAccount->shouldReceive('find')->with('acc123')->andReturnSelf();

        $repository = new AccountRepository($dbAccount);
        $account = $repository->findById('acc123');

        $this->assertInstanceOf(Account::class, $account);
        $this->assertSame('acc123', $account->id());
        $this->assertSame('Walter', $account->name());
        $this->assertSame(100.0, $account->balance());
    }

    /** @test */
    public function testUpdateBalance(): void
    {
        $dbAccount = Mockery::mock(DbAccount::class)->makePartial();
        $dbAccount->id = 'acc123';
        $dbAccount->name = 'Walter';
        $dbAccount->balance = 100.0;
        $dbAccount->created_at = new DateTime('2025-01-01 12:00:00');
        $dbAccount->updated_at = new DateTime('2025-01-01 12:00:00');
        $dbAccount->deleted_at = null;

        $dbAccount->shouldReceive('find')->with('acc123')->andReturnSelf();
        $dbAccount->shouldReceive('update')->with(['balance' => 200.0])
            ->andReturnUsing(function ($attrs) use ($dbAccount) {
                $dbAccount->balance = $attrs['balance'];
                return true;
            });

        $repository = new AccountRepository($dbAccount);
        $account = new Account(id: 'acc123', name: 'Walter', balance: 200.0);
        $result = $repository->updateBalance($account);

        $this->assertSame(200.0, $result->balance());
    }

    /** @test */
    public function testSave(): void
    {
        $dbAccount = Mockery::mock(DbAccount::class)->makePartial();
        $dbAccount->id = 'acc123';
        $dbAccount->name = 'Walter';
        $dbAccount->balance = 100.0;
        $dbAccount->created_at = new DateTime('2025-01-01 12:00:00');
        $dbAccount->updated_at = new DateTime('2025-01-01 12:00:00');
        $dbAccount->deleted_at = null;

        $dbAccount->shouldReceive('updateOrInsert')
            ->with(['id' => 'acc123'], ['name' => 'Walter', 'balance' => 100.0])
            ->andReturnSelf();

        $repository = new AccountRepository($dbAccount);
        $account = new Account(id: 'acc123', name: 'Walter', balance: 100.0);
        $result = $repository->save($account);

        $this->assertSame('acc123', $result->id());
        $this->assertSame('Walter', $result->name());
        $this->assertSame(100.0, $result->balance());
    }
}
