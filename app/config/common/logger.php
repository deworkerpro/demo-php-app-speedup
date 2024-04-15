<?php

declare(strict_types=1);

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Log\LoggerInterface;

return [
    LoggerInterface::class => static function () {
        $logHandler = new StreamHandler('php://stderr');
        $logHandler->pushProcessor(new PsrLogMessageProcessor());

        $logger = new Logger('server');
        $logger->pushHandler($logHandler);

        return $logger;
    },
];
