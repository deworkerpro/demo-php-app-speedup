<?php

declare(strict_types=1);

use App\Http\Middleware\DomainExceptionHandler;
use App\Http\Middleware\ErrorHandler;
use FastRoute\Dispatcher;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use React\Http\HttpServer;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;
use React\Socket\SocketServer;

require __DIR__ . '/../vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$logger = $container->get(LoggerInterface::class);
$dispatcher = $container->get(Dispatcher::class);

$http = new HttpServer(
    $container->get(ErrorHandler::class),
    $container->get(DomainExceptionHandler::class),
    static function (ServerRequestInterface $request) use ($dispatcher): ResponseInterface|PromiseInterface {
        /**
         * @var array{
         *     int,
         *     callable(ServerRequestInterface):ResponseInterface,
         *     array<string, string>
         * } $match
         */
        $match = $dispatcher->dispatch(
            $request->getMethod(),
            $request->getUri()->getPath()
        );

        switch ($match[0]) {
            case Dispatcher::FOUND:
                $handler = $match[1];
                $vars = $match[2];
                foreach ($vars as $name => $value) {
                    $request = $request->withAttribute($name, $value);
                }
                return $handler($request);
            case Dispatcher::NOT_FOUND:
                return new Response(404);
            case Dispatcher::METHOD_NOT_ALLOWED:
                return new Response(405);
            default:
                throw new UnexpectedValueException('Unexpected dispatcher code ' . $match[0]);
        }
    }
);

$socket = new SocketServer('0.0.0.0:80');
$http->listen($socket);

$logger->info('Listening');
