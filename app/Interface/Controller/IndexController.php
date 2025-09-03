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

use App\Interface\Request\CreateAccountBalanceRequest;
use App\Interface\Request\CreateAccountRequest;
use App\Interface\Request\CreateWithdrawPixRequest;

class IndexController extends AbstractController
{

    public function balance(CreateAccountBalanceRequest $balance)
    {
        return 'balance';
    }

    public function account(CreateAccountRequest $account)
    {
        return 'account';
    }

    public function withdraw(CreateWithdrawPixRequest $withdraw)
    {
        return 'withdraw';
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
