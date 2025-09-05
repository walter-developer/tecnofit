<?php
// config/autoload/crontab.php

use App\Interface\Console\ProcessWithdraw;
use Hyperf\Crontab\Crontab;

return [
    'enable' => true,
    'crontab' => [
        (new Crontab())
            ->setName('Saques')->setRule('* * * * *')
            ->setCallback([ProcessWithdraw::class, 'handle'])
            ->setMemo('Processa saques agendados a cada minuto'),
    ],
];
