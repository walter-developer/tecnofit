<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Request;

use App\Interface\Request\CreateAccountRequest;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Mockery;

class CreateAccountRequestTest extends TestCase
{
    protected CreateAccountRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $container = Mockery::mock(ContainerInterface::class);

        // Passa o container para o construtor do FormRequest
        $this->request = new CreateAccountRequest($container);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function itAuthorizesRequest(): void
    {
        $this->assertTrue($this->request->authorize());
    }

    /** @test */
    public function itReturnsValidationRules(): void
    {
        $expectedRules = [
            'name' => 'required|string'
        ];

        $this->assertSame($expectedRules, $this->request->rules());
    }

    /** @test */
    public function itReturnsCustomMessages(): void
    {
        $expectedMessages = [
            'name.required' => 'O nome da conta é obrigatório.',
            'name.string' => 'O nome da conta deve ser um texto válido.',
        ];

        $this->assertSame($expectedMessages, $this->request->messages());
    }
}
