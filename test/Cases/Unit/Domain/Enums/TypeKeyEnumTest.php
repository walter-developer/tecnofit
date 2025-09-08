<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Domain\Enums;

use App\Domain\Enums\TypeKeyEnum;
use PHPUnit\Framework\TestCase;

class TypeKeyEnumTest extends TestCase
{
    /** @test */
    public function itIdentifiesCpf(): void
    {
        $key = '12345678901';
        $enum = TypeKeyEnum::RANDOM->identify($key);
        $this->assertSame(TypeKeyEnum::CPF, $enum);
        $this->assertSame(TypeKeyEnum::CPF, TypeKeyEnum::key($key));
    }

    /** @test */
    public function itIdentifiesCnpj(): void
    {
        $key = '12345678000199';
        $enum = TypeKeyEnum::RANDOM->identify($key);
        $this->assertSame(TypeKeyEnum::CNPJ, $enum);
        $this->assertSame(TypeKeyEnum::CNPJ, TypeKeyEnum::key($key));
    }

    /** @test */
    public function itIdentifiesEmail(): void
    {
        $key = 'user@example.com';
        $enum = TypeKeyEnum::RANDOM->identify($key);
        $this->assertSame(TypeKeyEnum::EMAIL, $enum);
        $this->assertSame(TypeKeyEnum::EMAIL, TypeKeyEnum::key($key));
    }

    /** @test */
    public function itIdentifiesTelephone(): void
    {
        $key = '+5511999998888';
        $enum = TypeKeyEnum::RANDOM->identify($key);
        $this->assertSame(TypeKeyEnum::TELEPHONE, $enum);
        $this->assertSame(TypeKeyEnum::TELEPHONE, TypeKeyEnum::key($key));
    }

    /** @test */
    public function itIdentifiesRandom(): void
    {
        $key = '123e4567-e89b-42d3-a456-426614174000';
        $enum = TypeKeyEnum::RANDOM->identify($key);
        $this->assertSame(TypeKeyEnum::RANDOM, $enum);
        $this->assertSame(TypeKeyEnum::RANDOM, TypeKeyEnum::key($key));
    }

    /** @test */
    public function itThrowsExceptionForInvalidKey(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid key format');

        TypeKeyEnum::RANDOM->identify('invalid-key');
    }
}
