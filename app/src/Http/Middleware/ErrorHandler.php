<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use React\Http\Message\Response;
use React\Promise\PromiseInterface;

use function React\Async\await;
use function React\Promise\resolve;

final readonly class ErrorHandler
{
    public function __construct(
        private LoggerInterface $logger,
    )
    {
    }

    public function __invoke(RequestInterface $request, callable $next): PromiseInterface|ResponseInterface
    {
        /** @var ResponseInterface */
        return resolve($next($request))
            ->catch(function (Exception $exception) use ($request) {
                $this->logger->error($exception->getMessage(), [
                    'exception' => $exception,
                    'url' => (string)$request->getUri(),
                ]);

                return new Response(
                    status: Response::STATUS_INTERNAL_SERVER_ERROR,
                    headers: ['content-type' => 'application/json'],
                    body: json_encode([
                        'message' => 'Server error.',
                    ]),
                );
            });
    }
}
