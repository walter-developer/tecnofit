<?php

declare(strict_types=1);

namespace App\Domain\Enums;

enum TypeKeyEnum: string
{
    case CPF = 'CPF';
    case CNPJ = 'CNPJ';
    case EMAIL = 'EMAIL';
    case TELEPHONE = 'TELEPHONE';
    case RANDOM = 'RANDOM';

    public static function key(string $key): self
    {
        return self::RANDOM->identify($key);
    }

    private function onlyNumber(string $key): string
    {
        return strval(preg_replace('/\D/', '', $key));
    }

    private function isCpf(string $key): bool
    {
        return preg_match('/^\d{11}$/', $this->onlyNumber($key));
    }

    private function isCnpj(string $key): bool
    {
        return preg_match('/^\d{14}$/', $this->onlyNumber($key));
    }

    private function isPhone(string $key): bool
    {
        return preg_match('/^\+?\d{10,15}$/', $this->onlyNumber($key));
    }

    private function isEmail(string $key): bool
    {
        return filter_var($key, FILTER_VALIDATE_EMAIL);
    }

    private function isRandom(string $key): bool
    {
        $match = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';
        return preg_match($match, $key);
    }

    public function identify(string $key): self
    {
        return match (true) {
            $this->isCpf($key) => self::CPF,
            $this->isCnpj($key) => self::CNPJ,
            $this->isEmail($key) => self::EMAIL,
            $this->isPhone($key) => self::TELEPHONE,
            $this->isRandom($key) => self::RANDOM,
            default => throw new \InvalidArgumentException('Invalid key format'),
        };
    }
}
