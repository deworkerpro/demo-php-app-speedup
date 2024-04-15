<?php

declare(strict_types=1);

use FastRoute\Dispatcher;
use OpenSwoole\HTTP\Request;
use OpenSwoole\HTTP\Response;
use OpenSwoole\HTTP\Server;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

require __DIR__ . '/../vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$logger = $container->get(LoggerInterface::class);

$server = new Server('0.0.0.0', 80);

$server->on('Request', static function (Request $request, Response $response) use ($container): void {
    $dispatcher = $container->get(Dispatcher::class);

    /**
     * @var array{
     *     int,
     *     callable(Request, Response):void,
     *     array<string, string>
     * } $match
     */
    $match = $dispatcher->dispatch(
        $request->getMethod(),
        parse_url($request->server['request_uri'], PHP_URL_PATH)
    );

    switch ($match[0]) {
        case Dispatcher::FOUND:
            $handler = $match[1];
            $vars = $match[2];
            $handler($request, $response, $vars);
            break;
        case Dispatcher::NOT_FOUND:
            $response->status(404);
            $response->end();
            break;
        case Dispatcher::METHOD_NOT_ALLOWED:
            $response->status(405);
            $response->end();
            break;
        default:
            throw new UnexpectedValueException('Unexpected dispatcher code ' . $match[0]);
    }
});

$server->start();
