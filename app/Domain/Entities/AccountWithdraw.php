<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Domain;
use App\Domain\Enums\MethodEnum;

class AccountWithdraw extends Domain
{
    public function __construct(
        public MethodEnum $method,
        public float $amount,
        public Account $account,
        public bool $done = false,
        public bool $error = false,
        public bool $scheduled = false,
        public ?string $errorReason = null,
        public ?\DateTime $schedule = null,
        public readonly ?string $id = null,
        public readonly ?\DateTime $createdAt = null,
        public readonly ?\DateTime $updatedAt = null,
        public readonly ?\DateTime $deletedAt = null,
    ) {}

    public function id(): string
    {
        return $this->id;
    }

    public function method(): MethodEnum
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

    public function schedule(): ?\DateTime
    {
        return $this->schedule;
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
