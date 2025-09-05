<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Domain;

class Account extends Domain
{

    public function __construct(
        public float $balance = 0,
        public ?string $name = null,
        public readonly ?string $id = null,
        public readonly ?\DateTime $createdAt = null,
        public readonly ?\DateTime $updatedAt = null,
        public readonly ?\DateTime $deletedAt = null,

    ) {}

    public function id(): ?string
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function balance(): float
    {
        return $this->balance;
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
