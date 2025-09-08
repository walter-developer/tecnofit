<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Infrastructure\Exception;

use App\Infrastructure\Exception\AppExceptionHandler;
use Hyperf\HttpMessage\Server\Response as ServerResponse;
use Hyperf\Validation\ValidationException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Hyperf\Validation\Validator;

class AppExceptionHandlerTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_handles_validation_exception(): void
    {
        $validator = Mockery::mock(Validator::class);
        $validator->shouldReceive('errors->all')
            ->andReturn(['O campo email é obrigatório']);

        $validationException = new ValidationException($validator);

        $handler = new AppExceptionHandler();
        $response = new ServerResponse();

        $result = $handler->handle($validationException, $response);

        $this->assertSame(422, $result->getStatusCode());
        $this->assertJson((string) $result->getBody());
        $decoded = json_decode((string) $result->getBody(), true);
        $this->assertEquals(['errors' => ['O campo email é obrigatório']], $decoded);
    }

    /** @test */
    public function it_handles_default_exception(): void
    {
        $exception = new class('Algo deu errado') extends \Exception {};

        $handler = new AppExceptionHandler();
        $response = new ServerResponse();

        $result = $handler->handle($exception, $response);

        $this->assertSame(422, $result->getStatusCode());
        $this->assertJson((string) $result->getBody());
        $decoded = json_decode((string) $result->getBody(), true);
        $this->assertEquals(['errors' => ['Algo deu errado']], $decoded);
    }

    /** @test */
    public function it_always_considers_exception_as_valid(): void
    {
        $handler = new AppExceptionHandler();
        $throwable = new \Exception('teste');

        $this->assertTrue($handler->isValid($throwable));
    }
}
