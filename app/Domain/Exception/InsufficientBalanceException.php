<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use RuntimeException;

class InsufficientBalanceException extends RuntimeException
{

    public function __construct(float $currentBalance, float $withdrawAmount)
    {
        $message = sprintf(
            'Saldo insuficiente. Saldo atual: %.2f, Valor do saque: %.2f',
            $currentBalance,
            $withdrawAmount
        );
        parent::__construct($message, 422);
    }
}
