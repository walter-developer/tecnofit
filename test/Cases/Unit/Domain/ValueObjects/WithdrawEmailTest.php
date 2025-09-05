<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Domain\ValueObjects;

use PHPUnit\Framework\TestCase;
use App\Domain\ValueObjects\WithdrawEmail;
use App\Domain\Entities\AccountWithdraw;
use App\Domain\Entities\Account;
use App\Domain\Enums\MethodEnum;
use DateTimeImmutable;

class WithdrawEmailTest extends TestCase
{
    public function testTemplateGeneratesCorrectBody(): void
    {
        // Criando uma conta fake
        $accountFake = $this->createMock(Account::class);

        // Criando a entidade AccountWithdraw real
        $withdraw = new AccountWithdraw(
            method: MethodEnum::PIX,       // use um valor válido do enum
            amount: 1234.56,
            account: $accountFake,
            updatedAt: new \DateTime('2025-09-05 18:00:00')
        );

        // Criando instância de WithdrawEmail
        $email = new WithdrawEmail('test@example.com', 'Saque realizado');

        // Chamando método template
        $email->template($withdraw);

        // Definindo corpo esperado
        $expectedBody = json_encode([
            'Titulo' => 'Seu saque foi relaizado com sucesso.',
            'Valor' => 'R$ 1.234,56',
            'Horario' => (new \DateTime('2025-09-05 18:00:00'))->format('d-m-Y H:i'),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // Asserções
        $this->assertSame('test@example.com', $email->to);
        $this->assertSame('Saque realizado', $email->subject);
        $this->assertSame($expectedBody, $email->body);
    }
}
