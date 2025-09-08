<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Console;

use App\Application\AccountApplication;
use App\Domain\Contracts\AccountWithdrawContract;
use App\Domain\Entities\AccountWithdraw;
use App\Interface\Console\ProcessWithdraw;
use Hyperf\Contract\StdoutLoggerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

class ProcessWithdrawTest extends TestCase
{
    protected AccountApplication $accountApplication;
    protected AccountWithdrawContract $accountWithdrawRepository;
    protected StdoutLoggerInterface $logger;
    protected ProcessWithdraw $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountApplication = Mockery::mock(AccountApplication::class);
        $this->accountWithdrawRepository = Mockery::mock(AccountWithdrawContract::class);
        $this->logger = Mockery::mock(StdoutLoggerInterface::class);

        $this->command = new ProcessWithdraw(
            $this->accountApplication,
            $this->accountWithdrawRepository,
            $this->logger
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function itProcessesScheduledWithdrawalsWithDefaultLimit(): void
    {
        $withdraw1 = Mockery::mock(AccountWithdraw::class);
        $withdraw2 = Mockery::mock(AccountWithdraw::class);

        $this->logger
            ->shouldReceive('info')
            ->once()
            ->with(Mockery::type('string'));

        $this->accountWithdrawRepository
            ->shouldReceive('chunkScheduledWithdrawals')
            ->once()
            ->with(Mockery::on(function ($callback) use ($withdraw1, $withdraw2) {
                $callback($withdraw1);
                $callback($withdraw2);
                return true;
            }), 20);

        $this->accountApplication
            ->shouldReceive('processWithdrawal')
            ->twice()
            ->with(Mockery::type(AccountWithdraw::class));

        $this->command->handle();

        $this->assertTrue(true);
    }

    /** @test */
    public function itProcessesScheduledWithdrawalsWithCustomLimit(): void
    {
        $scheduledWithdrawals = [
            Mockery::mock(AccountWithdraw::class),
            Mockery::mock(AccountWithdraw::class),
        ];

        $this->accountApplication
            ->shouldReceive('processWithdrawal')
            ->with(Mockery::type(AccountWithdraw::class))
            ->andReturnNull()
            ->times(count($scheduledWithdrawals));

        $this->accountWithdrawRepository
            ->shouldReceive('chunkScheduledWithdrawals')
            ->once()
            ->withArgs(function ($callback, $limit) use ($scheduledWithdrawals) {
                if ($limit !== 10) {
                    return false;
                }
                foreach ($scheduledWithdrawals as $withdraw) {
                    $callback($withdraw);
                }
                return true;
            });

        $this->logger->shouldReceive('info')->once();

        $input = Mockery::mock(\Symfony\Component\Console\Input\InputInterface::class);
        $input->shouldReceive('getArgument')->with('limit')->andReturn(10);

        $reflection = new \ReflectionClass($this->command);
        $property = $reflection->getProperty('input');
        $property->setAccessible(true);
        $property->setValue($this->command, $input);

        $this->command->handle();

        $this->assertTrue(true);
    }
}
