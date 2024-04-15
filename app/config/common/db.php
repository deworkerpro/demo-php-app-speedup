<?php

declare(strict_types=1);

use React\Mysql\MysqlClient;

use function App\env;

return [
    MysqlClient::class => static function (): MysqlClient {
        $host = env('DB_HOST');
        $user = env('DB_USER');
        $password = env('DB_PASSWORD');
        $name = env('DB_NAME');

        return new MysqlClient(
            rawurlencode($user) . ':' . rawurlencode($password) . "@{$host}/{$name}"
        );
    },
];
