<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Domain\Enums;

use App\Domain\Enums\MethodEnum;
use PHPUnit\Framework\TestCase;

class MethodEnumTest extends TestCase
{
    /** @test */
    public function itHasPixValue(): void
    {
        $enum = MethodEnum::PIX;

        $this->assertSame('PIX', $enum->value);
        $this->assertSame(MethodEnum::PIX, MethodEnum::from('PIX'));
    }
}
