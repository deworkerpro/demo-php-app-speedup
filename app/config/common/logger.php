<?php

declare(strict_types=1);

use Amp\ByteStream;
use Amp\Log\ConsoleFormatter;
use Amp\Log\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\PsrLogMessageProcessor;
use Psr\Log\LoggerInterface;

return [
    LoggerInterface::class => static function () {
        $logHandler = new StreamHandler(ByteStream\getStdout());
        $logHandler->pushProcessor(new PsrLogMessageProcessor());
        $logHandler->setFormatter(new ConsoleFormatter());

        $logger = new Logger('server');
        $logger->pushHandler($logHandler);

        return $logger;
    },
];
