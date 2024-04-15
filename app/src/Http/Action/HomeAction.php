<?php

declare(strict_types=1);

namespace App\Http\Action;

use OpenSwoole\HTTP\Request;
use OpenSwoole\HTTP\Response;
use stdClass;

final readonly class HomeAction
{
    public function __invoke(Request $request, Response $response): void
    {
        $response->header('content-type', 'application/json');
        $response->end(json_encode(new stdClass()));
    }
}
