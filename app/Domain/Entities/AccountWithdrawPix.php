<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Domain;
use App\Domain\Enums\TypeKeyEnum;

class AccountWithdrawPix extends Domain
{
    public function __construct(
        public string $key,
        public TypeKeyEnum $type,
        public AccountWithdraw $accountWithdraw,
        public readonly ?string $id = null,
        public readonly ?\DateTime $createdAt = null,
        public readonly ?\DateTime $updatedAt = null,
        public readonly ?\DateTime $deletedAt = null,
    ) {}

    public function id(): ?string
    {
        return $this->id;
    }

    public function key(): string
    {
        return $this->key;
    }

    public function accountWithdraw(): AccountWithdraw
    {
        return $this->accountWithdraw;
    }

    public function type(): TypeKeyEnum
    {
        return $this->type;
    }

    public function createdAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }
}
