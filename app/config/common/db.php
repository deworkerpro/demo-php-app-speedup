<?php

declare(strict_types=1);

use Amp\Postgres\PostgresConfig;
use Amp\Postgres\PostgresConnectionPool;
use Amp\Sql\SqlLink;

use function App\env;

return [
    SqlLink::class => static function () {
        $config = new PostgresConfig(
            host: env('DB_HOST'),
            user: env('DB_USER'),
            password: env('DB_PASSWORD'),
            database: env('DB_NAME')
        );

        return new PostgresConnectionPool($config);
    },
];
