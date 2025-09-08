<?php

declare(strict_types=1);

namespace HyperfTest\Cases\Unit\Controller;

use App\Application\AccountApplication;
use App\Interface\Controller\IndexController;
use App\Interface\Request\CreateAccountRequest;
use App\Interface\Request\CreateWithdrawPixRequest;
use App\Interface\Request\UpdateAccountBalanceRequest;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
use Mockery;
use PHPUnit\Framework\TestCase;

class IndexControllerTest extends TestCase
{
    protected IndexController $controller;
    protected $accountMock;
    protected $requestMock;
    protected $responseMock;
    protected $containerMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountMock = Mockery::mock(AccountApplication::class);
        $this->requestMock = Mockery::mock(RequestInterface::class);
        $this->responseMock = Mockery::mock(ResponseInterface::class);
        $this->containerMock = Mockery::mock(ContainerInterface::class);

        $this->controller = new IndexController(
            $this->containerMock,
            $this->requestMock,
            $this->responseMock,
            $this->accountMock
        );
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function itUpdatesBalance(): void
    {
        $validatedData = ['id' => 'acc123', 'balance' => 200];

        $request = Mockery::mock(UpdateAccountBalanceRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($validatedData);

        $this->accountMock
            ->shouldReceive('updateBalance')
            ->once()
            ->with($validatedData)
            ->andReturn(['key' => 'acc123', 'balance' => 200]);

        $result = $this->controller->balance($request);

        $this->assertSame(['key' => 'acc123', 'balance' => 200], $result);
    }

    /** @test */
    public function itCreatesAccount(): void
    {
        $validatedData = ['name' => 'Walter'];

        $request = Mockery::mock(CreateAccountRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($validatedData);

        $this->accountMock
            ->shouldReceive('createAccount')
            ->once()
            ->with($validatedData)
            ->andReturn(['key' => 'acc123', 'name' => 'Walter', 'balance' => 0]);

        $result = $this->controller->account($request);

        $this->assertSame(['key' => 'acc123', 'name' => 'Walter', 'balance' => 0], $result);
    }

    /** @test */
    public function itCreatesWithdraw(): void
    {
        $validatedData = ['account_id' => 'acc123', 'amount' => 100];

        $request = Mockery::mock(CreateWithdrawPixRequest::class);
        $request->shouldReceive('validated')->once()->andReturn($validatedData);

        $this->accountMock
            ->shouldReceive('createWithdraw')
            ->once()
            ->with($validatedData)
            ->andReturn(['amount' => 100, 'method' => 'PIX']);

        $result = $this->controller->withdraw($request);

        $this->assertSame(['amount' => 100, 'method' => 'PIX'], $result);
    }

    /** @test */
    public function itReturnsIndexMessage(): void
    {
        $this->requestMock
            ->shouldReceive('input')
            ->with('user', 'Hyperf')
            ->once()
            ->andReturn('Walter');

        $this->requestMock
            ->shouldReceive('getMethod')
            ->once()
            ->andReturn('GET');

        $result = $this->controller->index();

        $this->assertSame([
            'method' => 'GET',
            'message' => 'Hello Walter 123.',
        ], $result);
    }
}
