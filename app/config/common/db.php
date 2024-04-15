<?php

declare(strict_types=1);

use OpenSwoole\Core\Coroutine\Client\PostgresClientFactory;
use OpenSwoole\Core\Coroutine\Client\PostgresConfig;
use OpenSwoole\Core\Coroutine\Pool\ClientPool;

use function App\env;

return [
    ClientPool::class => static function () {
        $config = (new PostgresConfig())
            ->withHost(env('DB_HOST'))
            ->withUsername(env('DB_USER'))
            ->withPassword(env('DB_PASSWORD'))
            ->withDbname(env('DB_NAME'));

        return new ClientPool(PostgresClientFactory::class, $config, 100);
    },
];
