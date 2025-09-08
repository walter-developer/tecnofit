<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Infrastructure\Repositories;

use App\Domain\Entities\AccountWithdraw;
use App\Domain\ValueObjects\WithdrawEmail;
use App\Infrastructure\Repositories\WithdrawEmailRepository;
use PHPMailer\PHPMailer\PHPMailer;
use PHPUnit\Framework\TestCase;
use Mockery;
use App\Domain\Enums\MethodEnum;
use App\Domain\Entities\Account;

class WithdrawEmailRepositoryTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function itCreatesMailerInstance(): void
    {
        $repository = new class extends WithdrawEmailRepository {};

        $reflection = new \ReflectionClass($repository);
        $method = $reflection->getMethod('createMailer');
        $method->setAccessible(true);

        $mailer = $method->invoke($repository);

        $this->assertInstanceOf(\PHPMailer\PHPMailer\PHPMailer::class, $mailer);
        $this->assertTrue($mailer->SMTPDebug === 0 || $mailer->SMTPDebug === null);
    }

    /** @test */
    public function itSendsEmailSuccessfully(): void
    {
        $account = new Account(
            id: 'acc123',
            name: 'Walter',
            balance: 100.0
        );

        $withdraw = new AccountWithdraw(
            id: 'with123',
            method: MethodEnum::PIX,
            amount: 50.0,
            account: $account
        );

        $message = (new WithdrawEmail(
            to: 'destino@exemplo.com',
            subject: 'Saque realizado'
        ))->template($withdraw);

        $mailMock = Mockery::mock(PHPMailer::class)->makePartial();
        $mailMock->shouldReceive('send')->once()->andReturnTrue();

        $repository = new class($mailMock) extends WithdrawEmailRepository {
            private $mailMock;
            public function __construct($mailMock)
            {
                $this->mailMock = $mailMock;
            }

            protected function createMailer(): PHPMailer
            {
                return $this->mailMock;
            }
        };

        $repository->send($message);

        $this->assertTrue(true);
    }

    /** @test */
    public function itThrowsRuntimeExceptionOnFailure(): void
    {
        $account = new Account(
            id: 'acc123',
            name: 'Walter',
            balance: 100.0
        );

        $withdraw = new AccountWithdraw(
            id: 'with123',
            method: MethodEnum::PIX,
            amount: 50.0,
            account: $account
        );

        $message = (new WithdrawEmail(
            to: 'destino@exemplo.com',
            subject: 'Saque realizado'
        ))->template($withdraw);

        $mailMock = Mockery::mock(PHPMailer::class)->makePartial();
        $mailMock->shouldReceive('send')->once()->andReturnUsing(function () use ($mailMock) {
            $mailMock->ErrorInfo = 'Falha no envio';
            throw new \PHPMailer\PHPMailer\Exception('Falha no envio');
        });

        $repository = new class($mailMock) extends WithdrawEmailRepository {
            private $mailMock;
            public function __construct($mailMock)
            {
                $this->mailMock = $mailMock;
            }

            protected function createMailer(): PHPMailer
            {
                return $this->mailMock;
            }
        };

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Erro ao enviar e-mail: Falha no envio');

        $repository->send($message);
    }
}
