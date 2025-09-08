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

use App\Interface\Request\CreateAccountRequest;
use App\Interface\Request\CreateWithdrawPixRequest;
use App\Interface\Request\UpdateAccountBalanceRequest;

class IndexController extends AbstractController
{

    public function balance(UpdateAccountBalanceRequest $balance)
    {
        $this->logger->info('Requisição recebida ( Recarga de saldo )', [
            'method' => $balance->getMethod(),
            'path' => $balance->path(),
            'body' => $balance->validated(),
        ]);

        return $this->account->updateBalance($balance->validated());
    }

    public function account(CreateAccountRequest $account)
    {
        $this->logger->info('Requisição recebida ( Nova Conta )', [
            'method' => $account->getMethod(),
            'path' => $account->path(),
            'body' => $account->validated(),
        ]);

        return $this->account->createAccount($account->validated());
    }

    public function withdraw(CreateWithdrawPixRequest $withdraw)
    {
        $this->logger->info('Requisição recebida ( Saque solicitado )', [
            'method' => $withdraw->getMethod(),
            'path' => $withdraw->path(),
            'body' => $withdraw->validated(),
        ]);

        return $this->account->createWithdraw($withdraw->validated());
    }

    public function index()
    {
        $user = $this->request->input('user', 'Hyperf');
        $method = $this->request->getMethod();
        return [
            'method' => $method,
            'message' => "Hello {$user} 123.",
        ];
    }
}
