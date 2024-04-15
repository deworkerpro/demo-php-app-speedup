<?php

declare(strict_types=1);

namespace App\Http\Action;

use Amp\Http\HttpStatus;
use Amp\Http\Server\Request;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\Response;

final readonly class HomeAction implements RequestHandler
{
    public function handleRequest(Request $request): Response
    {
        return new Response(
            status: HttpStatus::OK,
            headers: ['content-type' => 'application/json'],
            body: '{}',
        );
    }
}
