<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Amp\Http\HttpStatus;
use Amp\Http\Server\Middleware;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;
use DomainException;
use Psr\Log\LoggerInterface;

final readonly class DomainExceptionHandler implements Middleware
{
    public function __construct(
        private LoggerInterface $logger,
    ) {}

    public function handleRequest(Request $request, RequestHandler $requestHandler): Response
    {
        try {
            return $requestHandler->handleRequest($request);
        } catch (DomainException $exception) {
            $this->logger->warning($exception->getMessage(), [
                'exception' => $exception,
                'url' => (string)$request->getUri(),
            ]);

            return new Response(
                status: HttpStatus::CONFLICT,
                headers: ['content-type' => 'application/json'],
                body: json_encode([
                    'message' => $exception->getMessage(),
                ]),
            );
        }
    }
}
