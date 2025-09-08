<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Domain;

use App\Domain\Domain;
use App\Domain\Enums\MethodEnum;
use DateTime;
use PHPUnit\Framework\TestCase;

class DomainTest extends TestCase
{
    /** @test */
    public function itConvertsPropertiesToArray(): void
    {
        $dt = new DateTime('2025-09-08 12:00:00');

        $testDomain = new class($dt) extends Domain {
            public string $name = 'Walter';
            public float $balance = 100.0;
            public MethodEnum $method;
            public ?DateTime $createdAt;

            public function __construct(DateTime $createdAt)
            {
                $this->method = MethodEnum::PIX;
                $this->createdAt = $createdAt;
            }
        };

        $result = $testDomain->toArray();

        $this->assertSame([
            'name' => 'Walter',
            'balance' => 100.0,
            'method' => 'PIX',
            'createdAt' => '2025-09-08 12:00:00',
        ], $result);
    }

    /** @test */
    public function itConvertsNestedDomainsToArray(): void
    {
        $nested = new class extends Domain {
            public string $nestedProp = 'nested';
        };

        $testDomain = new class($nested) extends Domain {
            public string $prop = 'value';
            public Domain $nested;

            public function __construct(Domain $nested)
            {
                $this->nested = $nested;
            }
        };

        $result = $testDomain->toArray();

        $this->assertSame([
            'prop' => 'value',
            'nested' => ['nestedProp' => 'nested'],
        ], $result);
    }

    /** @test */
    public function itFormatsUnitEnumCorrectly(): void
    {
        $testDomain = new class extends Domain {
            public MethodEnum $method;
            public function __construct()
            {
                $this->method = MethodEnum::PIX;
            }
        };

        $result = $testDomain->toArray();

        $this->assertSame(['method' => 'PIX'], $result);
    }

    /** @test */
    public function itFormatsDateTimeCorrectly(): void
    {
        $dt = new DateTime('2025-09-08 15:30:00');

        $testDomain = new class($dt) extends Domain {
            public DateTime $createdAt;
            public function __construct(DateTime $createdAt)
            {
                $this->createdAt = $createdAt;
            }
        };

        $result = $testDomain->toArray();

        $this->assertSame(['createdAt' => '2025-09-08 15:30:00'], $result);
    }
}
