<?php

declare(strict_types=1);

namespace App\Http\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use React\Http\Message\Response;
use stdClass;

final readonly class HomeAction
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return Response::json(new stdClass());
    }
}
