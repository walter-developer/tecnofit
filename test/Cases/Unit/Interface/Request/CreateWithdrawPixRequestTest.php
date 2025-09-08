<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Interface\Request;

use App\Interface\Request\CreateWithdrawPixRequest;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Hyperf\Context\RequestContext;
use Mockery;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Swow\Psr7\Message\ServerRequestPlusInterface;

class CreateWithdrawPixRequestTest extends TestCase
{
    protected CreateWithdrawPixRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $container = Mockery::mock(ContainerInterface::class);
        $this->request = new CreateWithdrawPixRequest($container);
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
        $rules = $this->request->rules();

        $this->assertArrayHasKey('account_id', $rules);
        $this->assertArrayHasKey('method', $rules);
        $this->assertArrayHasKey('amount', $rules);
        $this->assertArrayHasKey('pix', $rules);
        $this->assertArrayHasKey('pix.type', $rules);
        $this->assertArrayHasKey('pix.key', $rules);
        $this->assertArrayHasKey('schedule', $rules);

        $this->assertSame('required|uuid', $rules['account_id']);
        $this->assertSame('required|in:pix', $rules['method']);
        $this->assertSame('required|numeric|gt:0', $rules['amount']);
        $this->assertSame('required|array', $rules['pix']);
        $this->assertSame('required|in:email', $rules['pix.type']);
        $this->assertSame('required|string', $rules['pix.key']);
        $this->assertSame('nullable|date_format:Y-m-d H:i|after:now|before:+7 days', $rules['schedule']);
    }

    /** @test */
    public function itReturnsCustomMessages(): void
    {
        $messages = $this->request->messages();

        $this->assertArrayHasKey('account_id.required', $messages);
        $this->assertArrayHasKey('account_id.uuid', $messages);
        $this->assertArrayHasKey('method.required', $messages);
        $this->assertArrayHasKey('method.in', $messages);
        $this->assertArrayHasKey('amount.required', $messages);
        $this->assertArrayHasKey('amount.float', $messages);
        $this->assertArrayHasKey('schedule.date_format', $messages);
        $this->assertArrayHasKey('schedule.after', $messages);
        $this->assertArrayHasKey('schedule.before', $messages);
        $this->assertArrayHasKey('pix.required', $messages);
        $this->assertArrayHasKey('pix.array', $messages);
        $this->assertArrayHasKey('pix.type.required', $messages);
        $this->assertArrayHasKey('pix.type.in', $messages);
        $this->assertArrayHasKey('pix.key.required', $messages);
        $this->assertArrayHasKey('pix.key.string', $messages);

        $this->assertSame('O ID da conta é obrigatório.', $messages['account_id.required']);
        $this->assertSame('O método de saque é obrigatório.', $messages['method.required']);
        $this->assertSame('O valor do saque é obrigatório.', $messages['amount.required']);
    }

    /** @test */
    public function itValidationDataReturnsArray(): void
    {
        $mockServerRequest = Mockery::mock(ServerRequestPlusInterface::class, ServerRequestInterface::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mockServerRequest->shouldReceive('all')
            ->andReturn(['id' => 'data-id', 'schedule' => '2025-12-01 12:00']);
        $mockServerRequest->shouldReceive('route')
            ->with('accountId', Mockery::any())
            ->andReturn('data-id');
        $mockServerRequest->shouldReceive('getParsedBody')
            ->andReturn(['id' => 'data-id', 'schedule' => '2025-12-01 12:00']);
        $mockServerRequest->shouldReceive('getQueryParams')
            ->andReturn([]);
        $mockServerRequest->shouldReceive('getMethod')
            ->andReturn('POST');
        $mockServerRequest->shouldReceive('getUri')
            ->andReturn(Mockery::mock(UriInterface::class));
        $mockServerRequest->shouldReceive('getServerParams')
            ->andReturn([]);
        $mockServerRequest->shouldReceive('getCookieParams')
            ->andReturn([]);
        $mockServerRequest->shouldReceive('getUploadedFiles')
            ->andReturn([]);
        $mockServerRequest->shouldReceive('getAttributes')
            ->andReturn([]);
        $mockServerRequest->shouldReceive('getAttribute')
            ->withAnyArgs()
            ->andReturn(null);

        RequestContext::set($mockServerRequest);

        $request = new CreateWithdrawPixRequest(Mockery::mock(ContainerInterface::class));

        $data = $request->validationData();

        $this->assertIsArray($data);
        $this->assertSame('data-id', $data['account_id']);
        $this->assertSame('2025-12-01 12:00', $data['schedule']);
    }
}
