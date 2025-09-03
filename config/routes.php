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

use App\Interface\Controller\IndexController;
use Hyperf\HttpServer\Router\Router;

Router::get('/',  [IndexController::class, 'index']);

Router::addGroup('/account', function () {
    ## http://localhost/account/
    ## Nova Conta
    Router::post('/',  [IndexController::class, 'account']);

    ## http://localhost/account/{accountId}
    ## Adicionar Credito a Conta
    Router::post('/{accountId}', [IndexController::class, 'balance']);

    ## http://localhost/account/{accountId}/balance/withdraw
    ## Efetuar saque da conta
    Router::post('/{accountId}/balance/withdraw', [IndexController::class, 'withdraw']);
});
