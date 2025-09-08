<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Interface\Controller;

use App\Application\AccountApplication;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractController
{
    public function __construct(
        protected ContainerInterface $container,
        protected RequestInterface $request,
        protected ResponseInterface $response,
        protected AccountApplication $account,
        protected LoggerInterface $logger
    ) {}
}
