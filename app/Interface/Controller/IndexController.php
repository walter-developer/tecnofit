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
        return $this->account->updateBalance($balance->validated());
    }

    public function account(CreateAccountRequest $account)
    {
        return $this->account->createAccount($account->validated());
    }

    public function withdraw(CreateWithdrawPixRequest $withdraw)
    {
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
