<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Domain\Exception;

use App\Domain\Exception\InvalidAccountException;
use PHPUnit\Framework\TestCase;

class InvalidAccountExceptionTest extends TestCase
{
    /** @test */
    public function itConstructsWithDefaultMessageAndCode(): void
    {
        $exception = new InvalidAccountException();

        $this->assertSame(
            'Conta não encontrada no banco de dados',
            $exception->getMessage()
        );

        $this->assertSame(422, $exception->getCode());
    }

    /** @test */
    public function itAllowsCustomMessage(): void
    {
        $customMessage = 'Conta inválida';
        $exception = new InvalidAccountException($customMessage);

        $this->assertSame($customMessage, $exception->getMessage());
        $this->assertSame(422, $exception->getCode());
    }
}
