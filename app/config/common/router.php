<?php

declare(strict_types=1);

use App\Http\Action\HomeAction;
use App\Http\Action\V1\Blog\IndexAction;
use FastRoute\Dispatcher;
use FastRoute\RouteCollector;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use React\Http\Message\Response;

use function FastRoute\simpleDispatcher;

return [
    Dispatcher::class => static function (ContainerInterface $container) {
        return simpleDispatcher(static function (RouteCollector $r) use ($container): void {
            $r->addRoute('GET', '/health', static fn (): ResponseInterface => Response::plaintext('OK'));

            $r->addRoute('GET', '/', $container->get(HomeAction::class));
            $r->addRoute('GET', '/v1/blog', $container->get(IndexAction::class));
        });
    },
];
