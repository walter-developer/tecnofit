<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Domain;

class AccountWithdraw extends Domain
{
    public function __construct(
        public string $method,
        public float $amount,
        public bool $scheduled = false,
        public bool $done = false,
        public bool $error = false,
        public Account $account,
        public ?string $errorReason = null,
        public ?\DateTime $scheduledFor = null,
        public readonly ?string $id = null,
        public readonly ?\DateTime $createdAt = null,
        public readonly ?\DateTime $updatedAt = null,
        public readonly ?\DateTime $deletedAt = null,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    public function scheduled(): bool
    {
        return $this->scheduled;
    }

    public function done(): bool
    {
        return $this->done;
    }

    public function error(): bool
    {
        return $this->error;
    }

    public function errorReason(): ?string
    {
        return $this->errorReason;
    }

    public function account(): Account
    {
        return $this->account;
    }

    public function scheduledFor(): ?\DateTime
    {
        return $this->scheduledFor;
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
