<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use RuntimeException;

class InvalidAccountException extends RuntimeException
{
    public function __construct(string $message = 'Conta não encontrada no banco de dados')
    {
        parent::__construct($message, 422);
    }
}
