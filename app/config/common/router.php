<?php

declare(strict_types=1);

use App\Http\Action\HomeAction;
use App\Http\Action\V1\Blog\IndexAction;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use OpenSwoole\Http\Request;
use OpenSwoole\Http\Response;
use Psr\Container\ContainerInterface;

use function FastRoute\simpleDispatcher;

return [
    Dispatcher::class => static function (ContainerInterface $container) {
        return simpleDispatcher(static function (RouteCollector $r) use ($container): void {
            $r->addRoute('GET', '/health', static function (Request $request, Response $response): void {
                $response->end('OK');
            });

            $r->addRoute('GET', '/', $container->get(HomeAction::class));
            $r->addRoute('GET', '/v1/blog', $container->get(IndexAction::class));
        });
    },
];
