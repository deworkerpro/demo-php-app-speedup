<?php

declare(strict_types=1);

use Amp\Http\HttpStatus;
use Amp\Http\Server\ErrorHandler;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Server\Router;
use Amp\Http\Server\SocketHttpServer;
use App\Http\Action\HomeAction;
use App\Http\Action\V1\Blog\IndexAction;
use App\Http\Middleware\DomainExceptionHandler;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

require __DIR__ . '/../vendor/autoload.php';

/** @var ContainerInterface $container */
$container = require __DIR__ . '/../config/container.php';

$logger = $container->get(LoggerInterface::class);
$errorHandler = $container->get(ErrorHandler::class);

$server = SocketHttpServer::createForDirectAccess(
    logger: $logger,
    connectionLimitPerIp: 100 // increased for benchmarks
);

$router = new Router($server, $logger, $errorHandler);

$router->addMiddleware($container->get(DomainExceptionHandler::class));

$router->addRoute('GET', '/health', new class() implements RequestHandler {
    public function handleRequest(Request $request): Response
    {
        return new Response(
            status: HttpStatus::OK,
            headers: ['Content-Type' => 'text/plain'],
            body: 'OK',
        );
    }
});

$router->addRoute('GET', '/', $container->get(HomeAction::class));
$router->addRoute('GET', '/v1/blog', $container->get(IndexAction::class));

$server->expose('0.0.0.0:80');
$server->start($router, $errorHandler);

Amp\trapSignal([SIGINT, SIGTERM]);

$server->stop();
