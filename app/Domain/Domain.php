<?php

declare(strict_types=1);

namespace App\Domain;

use DateTime;
use UnitEnum;

class Domain
{

    public function toArray(): array
    {
        return array_map(fn($value) => match (true) {
            ($value instanceof Domain) => $value->toArray(),
            ($value instanceof UnitEnum) => $value->value,
            ($value instanceof DateTime) => $value->format('Y-m-d H:i:s'),
            default => $value,
        }, get_object_vars($this));
    }
}
