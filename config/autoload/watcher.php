<?php

declare(strict_types=1);

return [
    'bin' => 'php',
    'enable' => true,
    'driver' => \Hyperf\Watcher\Driver\ScanFileDriver::class,
    'watch' => [
        'dir' => ['app', 'config'],
        'file' => ['.env'],
        'scan_interval' => 2000,
    ],
];
