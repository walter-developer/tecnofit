<?php

declare(strict_types=1);

use App\Infrastructure\Exception\AppExceptionHandler;
use Hyperf\HttpServer\Exception\Handler\HttpExceptionHandler;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'handler' => [
        'http' => [
            HttpExceptionHandler::class,
            AppExceptionHandler::class,
        ],
    ],
];
