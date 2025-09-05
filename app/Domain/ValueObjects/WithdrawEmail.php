<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects;

use App\Domain\Entities\AccountWithdraw;

class WithdrawEmail
{
    public string $body;

    public function __construct(
        public string $to,
        public string $subject
    ) {}

    public  function template(AccountWithdraw $withdraw): static
    {
        $this->body = json_encode([
            'Titulo' => 'Seu saque foi relaizado com sucesso.',
            'Valor' => 'R$ ' . number_format($withdraw->amount(), 2, ',', '.'),
            'Horario' => $withdraw->updatedAt()?->format('d-m-Y H:i'),
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        return $this;
    }
}
