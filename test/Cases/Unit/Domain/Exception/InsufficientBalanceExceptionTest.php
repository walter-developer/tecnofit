<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Domain\Exception;

use App\Domain\Exception\InsufficientBalanceException;
use PHPUnit\Framework\TestCase;

class InsufficientBalanceExceptionTest extends TestCase
{
    /** @test */
    public function itConstructsWithCorrectMessageAndCode(): void
    {
        $currentBalance = 50.0;
        $withdrawAmount = 100.0;

        $exception = new InsufficientBalanceException($currentBalance, $withdrawAmount);

        $this->assertSame(
            'Saldo insuficiente. Saldo atual: 50.00, Valor do saque: 100.00',
            $exception->getMessage()
        );

        $this->assertSame(422, $exception->getCode());
    }
}
