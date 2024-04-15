<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use DomainException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;

use function React\Async\await;
use function React\Promise\resolve;

final readonly class DomainExceptionHandler
{
    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function __invoke(RequestInterface $request, callable $next): PromiseInterface|ResponseInterface
    {
        /** @var ResponseInterface */
        return resolve($next($request))
            ->catch(function (DomainException $exception) use ($request) {
                $this->logger->warning($exception->getMessage(), [
                    'exception' => $exception,
                    'url' => (string)$request->getUri(),
                ]);

                return new Response(
                    status: Response::STATUS_CONFLICT,
                    headers: ['content-type' => 'application/json'],
                    body: json_encode([
                        'message' => $exception->getMessage(),
                    ]),
                );
            });
    }
}
