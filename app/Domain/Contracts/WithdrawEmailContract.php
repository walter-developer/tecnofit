<?php

declare(strict_types=1);

namespace App\Domain\Contracts;

use App\Domain\ValueObjects\WithdrawEmail;

interface WithdrawEmailContract
{
    public function send(WithdrawEmail $message): void;
}
