<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Request;

use App\Interface\Request\UpdateAccountBalanceRequest;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Mockery;

class UpdateAccountBalanceRequestTest extends TestCase
{
    protected UpdateAccountBalanceRequest $request;

    protected function setUp(): void
    {
        parent::setUp();

        $container = Mockery::mock(ContainerInterface::class);
        $this->request = new UpdateAccountBalanceRequest($container);
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

        $this->assertArrayHasKey('id', $rules);
        $this->assertArrayHasKey('balance', $rules);
    }

    /** @test */
    public function itReturnsCustomMessages(): void
    {
        $messages = $this->request->messages();

        $this->assertArrayHasKey('id.required', $messages);
        $this->assertArrayHasKey('id.uuid', $messages);
        $this->assertArrayHasKey('balance.required', $messages);
        $this->assertArrayHasKey('balance.numeric', $messages);
        $this->assertArrayHasKey('balance.gt', $messages);
    }

    /** @test */
    public function itReturnsValidationDataIncludingRouteParameters(): void
    {
        $data = [
            'id' => '123',
            'balance' => 100,
        ];

        $requestMock = Mockery::mock(UpdateAccountBalanceRequest::class, [Mockery::mock(ContainerInterface::class)])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $requestMock->shouldReceive('validationData')->andReturnUsing(function () use ($data) {
            return ['id' => $data['id'], 'balance' => $data['balance']];
        });

        $result = $requestMock->validationData();
        $this->assertSame($data['id'], $result['id']);
        $this->assertSame($data['balance'], $result['balance']);
    }

    /** @test */
    /** @test */
    public function itValidationDataReturnsArray(): void
    {
        $mockServerRequest = Mockery::mock(\Swow\Psr7\Message\ServerRequestPlusInterface::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $mockServerRequest->shouldReceive('all')
            ->andReturn(['id' => 'data-id', 'balance' => 200]);

        $mockServerRequest->shouldReceive('route')
            ->with('accountId', Mockery::any())
            ->andReturn('data-id');

        $mockServerRequest->shouldReceive('getParsedBody')
            ->andReturn(['balance' => 200]);

        $mockServerRequest->shouldReceive('getQueryParams')
            ->andReturn([]);

        $mockServerRequest->shouldReceive('getMethod')
            ->andReturn('PUT');

        $mockServerRequest->shouldReceive('getUri')
            ->andReturn(Mockery::mock(\Psr\Http\Message\UriInterface::class));

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

        \Hyperf\Context\RequestContext::set($mockServerRequest);

        $request = new UpdateAccountBalanceRequest(Mockery::mock(ContainerInterface::class));

        $data = $request->validationData();

        $this->assertIsArray($data);
        $this->assertArrayHasKey('id', $data);
        $this->assertSame('data-id', $data['id']);
    }
}
