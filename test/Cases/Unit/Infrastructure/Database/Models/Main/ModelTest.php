<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Infrastructure\Database\Models\Main;

use App\Infrastructure\Database\Models\Main\Model;
use PHPUnit\Framework\TestCase;
use Hyperf\Database\Model\Builder;
use Mockery;

class ModelTest extends TestCase
{


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testIncrementingAndKeyType(): void
    {
        $model = new class extends Model {};
        $this->assertFalse($model->incrementing);
        $this->assertSame('string', $model->getKeyType());
    }

    public function testUpdateOrInsertReturnsModel(): void
    {
        $model = new class extends Model {
            public $called = false;

            public static function updateOrCreate(array $attributes, array $values)
            {
                $instance = new self();
                $instance->called = true;
                return $instance;
            }
        };

        $attributes = ['name' => 'Walter'];
        $values = ['balance' => 100];

        $result = $model->updateOrInsert($attributes, $values);

        $this->assertInstanceOf(Model::class, $result);
        $this->assertTrue($result->called);
    }

    public function testNewUniqueIdGeneratesUuid(): void
    {
        $model = new class extends \App\Infrastructure\Database\Models\Main\Model {
            public function getId(): string
            {
                return $this->id;
            }
        };

        $uuid = $model->newUniqueId();
        $this->assertIsString($uuid);
        $this->assertNotEmpty($uuid);
    }

    public function testPerformInsertIntegration(): void
    {
        $model = new class extends Model {
            public function getTable(): string
            {
                return 'test_table';
            }

            public function getFillable(): array
            {
                return ['name'];
            }

            public function getKeyName(): string
            {
                return 'id';
            }
        };

        $builderMock = Mockery::mock(Builder::class);
        $builderMock->shouldReceive('insert')->andReturn(true);

        $reflection = new \ReflectionClass($model);
        $attributesProperty = $reflection->getProperty('attributes');
        $attributesProperty->setAccessible(true);
        $attributesProperty->setValue($model, ['name' => 'Test']);

        $method = $reflection->getMethod('performInsert');
        $method->setAccessible(true);

        $result = $method->invoke($model, $builderMock);

        $this->assertTrue($result);
        $this->assertNotNull($model->getKey());
    }
}
