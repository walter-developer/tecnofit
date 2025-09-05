<?php

declare(strict_types=1);

namespace App\Infrastructure\Exception;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationException;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response): ResponseInterface
    {
        $this->stopPropagation();
        return match (true) {
            $throwable instanceof ValidationException
            => $this->validate($throwable, $response),
            default => $this->default($throwable, $response)
        };
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }


    public function validate(Throwable $throwable, ResponseInterface $response)
    {
        return $response
            ->withStatus(422)
            ->withBody(new SwooleStream(json_encode([
                'errors' => $throwable->validator->errors()->all(),
            ])));
    }

    public function default(Throwable $throwable, ResponseInterface $response)
    {
        return $response
            ->withStatus(422)
            ->withBody(new SwooleStream(json_encode([
                'errors' => [$throwable->getMessage()],
            ])));
    }
}
