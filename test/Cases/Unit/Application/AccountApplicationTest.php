<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Application;

use App\Application\AccountApplication;
use App\Domain\Contracts\AccountContract;
use App\Domain\Contracts\AccountWithdrawContract;
use App\Domain\Contracts\AccountWithdrawPixContract;
use App\Domain\Contracts\WithdrawEmailContract;
use App\Domain\Entities\Account;
use App\Domain\Entities\AccountWithdraw;
use App\Domain\Entities\AccountWithdrawPix;
use App\Domain\Enums\MethodEnum;
use App\Domain\Enums\TypeKeyEnum;
use App\Domain\Exception\InsufficientBalanceException;
use App\Domain\Exception\InvalidAccountException;
use App\Domain\ValueObjects\WithdrawEmail;
use Mockery;
use PHPUnit\Framework\TestCase;

class AccountApplicationTest extends TestCase
{
    protected AccountContract $accountRepository;
    protected AccountWithdrawContract $accountWithdrawRepository;
    protected AccountWithdrawPixContract $accountWithdrawPixRepository;
    protected WithdrawEmailContract $withdrawEmailRepository;
    protected AccountApplication $app;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountRepository = Mockery::mock(AccountContract::class);
        $this->accountWithdrawRepository = Mockery::mock(AccountWithdrawContract::class);
        $this->accountWithdrawPixRepository = Mockery::mock(AccountWithdrawPixContract::class);
        $this->withdrawEmailRepository = Mockery::mock(WithdrawEmailContract::class);

        $this->app = new AccountApplication(
            $this->accountRepository,
            $this->accountWithdrawRepository,
            $this->accountWithdrawPixRepository,
            $this->withdrawEmailRepository
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function itCreatesAccountSuccessfully(): void
    {
        $this->accountRepository
            ->shouldReceive('save')
            ->once()
            ->andReturn(new Account(id: 'acc123', name: 'Walter', balance: 0));

        $result = $this->app->createAccount(['name' => 'Walter']);

        $this->assertSame('acc123', $result['key']);
        $this->assertSame('Walter', $result['name']);
        $this->assertEquals(0, $result['balance']);
    }

    /** @test */
    public function itUpdatesBalanceSuccessfully(): void
    {
        $this->accountRepository
            ->shouldReceive('existsById')
            ->with('acc123')
            ->once()
            ->andReturnTrue();

        $this->accountRepository
            ->shouldReceive('updateBalance')
            ->once()
            ->andReturn(new Account(id: 'acc123', balance: 200));

        $result = $this->app->updateBalance(['id' => 'acc123', 'balance' => 200]);

        $this->assertSame('acc123', $result['key']);
        $this->assertEquals(200, $result['balance']);
    }

    /** @test */
    public function itThrowsExceptionIfAccountDoesNotExistOnUpdate(): void
    {
        $this->accountRepository
            ->shouldReceive('existsById')
            ->with('acc123')
            ->once()
            ->andReturnFalse();

        $this->expectException(InvalidAccountException::class);

        $this->app->updateBalance(['id' => 'acc123', 'balance' => 200]);
    }

    /** @test */
    public function itCreatesWithdrawSuccessfully(): void
    {
        $account = new Account(id: 'acc123', balance: 500);
        $withdraw = new AccountWithdraw(method: MethodEnum::PIX, amount: 100, account: $account);
        $withdrawPix = new AccountWithdrawPix(accountWithdraw: $withdraw, key: 'EMAIL', type: TypeKeyEnum::EMAIL);

        $this->accountRepository
            ->shouldReceive('existsById')
            ->with('acc123')
            ->once()
            ->andReturnTrue();

        $this->accountRepository
            ->shouldReceive('findById')
            ->with('acc123')
            ->once()
            ->andReturn($account);

        $this->accountRepository
            ->shouldReceive('updateBalance')
            ->once()
            ->andReturn(new Account(id: 'acc123', balance: 400));

        $this->accountWithdrawRepository
            ->shouldReceive('save')
            ->twice()
            ->andReturn($withdraw);

        $this->accountWithdrawPixRepository
            ->shouldReceive('save')
            ->once()
            ->andReturn($withdrawPix);

        $this->withdrawEmailRepository
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::type(WithdrawEmail::class));

        $result = $this->app->createWithdraw([
            'account_id' => 'acc123',
            'amount' => 100,
            'method' => 'PIX',
            'schedule' => false,
            'pix' => ['key' => 'test@test.com']
        ]);

        $this->assertSame('EMAIL', $result['key']);
        $this->assertSame('email', $result['type']);
        $this->assertEquals(100, $result['amount']);
        $this->assertSame('pix', $result['method']);
        $this->assertNull($result['schedule']);
    }

    /** @test */
    public function itThrowsInsufficientBalanceException(): void
    {
        $account = new Account(id: 'acc123', balance: 50);

        $this->accountRepository
            ->shouldReceive('existsById')
            ->with('acc123')
            ->once()
            ->andReturnTrue();

        $this->accountRepository
            ->shouldReceive('findById')
            ->with('acc123')
            ->once()
            ->andReturn($account);

        $this->expectException(InsufficientBalanceException::class);

        $this->app->createWithdraw([
            'account_id' => 'acc123',
            'amount' => 100,
            'method' => 'PIX',
            'schedule' => false,
            'pix' => ['key' => 'EMAIL']
        ]);
    }

    /** @test */
    public function itProcessesWithdrawalAndHandlesFailure(): void
    {
        $account = new Account(id: 'acc123', balance: 500);

        $withdrawMock = Mockery::mock(AccountWithdraw::class);
        $withdrawMock->shouldReceive('id')->andReturn('withdraw-123');
        $withdrawMock->shouldReceive('account')->andReturn($account);
        $withdrawMock->shouldReceive('amount')->andReturn(100.0);
        $withdrawMock->shouldReceive('method')->andReturn(MethodEnum::PIX);
        $withdrawMock->shouldReceive('done')->andReturn(false);
        $withdrawMock->shouldReceive('scheduled')->andReturn(false);
        $withdrawMock->shouldReceive('schedule')->andReturn(null);
        $withdrawMock->shouldReceive('error')->andReturn(false);
        $withdrawMock->shouldReceive('errorReason')->andReturn(null);

        $this->accountWithdrawPixRepository
            ->shouldReceive('findWithdrawById')
            ->once()
            ->with('withdraw-123')
            ->andThrow(new \Exception('Fail'));

        $this->accountWithdrawRepository
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::type(AccountWithdraw::class))
            ->andReturn($withdrawMock);

        $this->withdrawEmailRepository
            ->shouldReceive('send')
            ->never();

        $this->app->processWithdrawal($withdrawMock);

        $this->assertTrue(true);
    }

    /** @test */
    public function itProcessesWithdrawalSuccessfully(): void
    {
        $account = new Account(id: 'acc123', balance: 500);
        $withdraw = new AccountWithdraw(method: MethodEnum::PIX, amount: 100, account: $account, id: 'with123');

        $this->accountWithdrawPixRepository
            ->shouldReceive('findWithdrawById')
            ->once()
            ->with('with123')
            ->andReturn(new AccountWithdrawPix(accountWithdraw: $withdraw, key: 'test@example.com', type: TypeKeyEnum::EMAIL));

        $this->accountWithdrawRepository
            ->shouldReceive('save')
            ->once()
            ->with(Mockery::type(AccountWithdraw::class));

        $this->withdrawEmailRepository
            ->shouldReceive('send')
            ->once()
            ->with(Mockery::type(WithdrawEmail::class));

        $this->app->processWithdrawal($withdraw);

        $this->assertTrue(true);
    }
}
