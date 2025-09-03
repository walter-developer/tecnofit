<?php

declare(strict_types=1);

use App\Domain\Contracts\AccountContract;
use App\Domain\Contracts\AccountWithdrawContract;
use App\Domain\Contracts\AccountWithdrawPixContract;
use App\Infrastructure\Repositories\AccountRepository;
use App\Infrastructure\Repositories\AccountWithdrawPixRepository;
use App\Infrastructure\Repositories\AccountWithdrawRepository;
use Hyperf\Watcher\Watcher;

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    AccountContract::class => AccountRepository::class,
    AccountWithdrawContract::class => AccountWithdrawRepository::class,
    AccountWithdrawPixContract::class => AccountWithdrawPixRepository::class,
];
