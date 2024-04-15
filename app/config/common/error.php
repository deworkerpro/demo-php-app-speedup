<?php

declare(strict_types=1);

use Amp\Http\Server\DefaultErrorHandler;
use Amp\Http\Server\ErrorHandler;

return [
    ErrorHandler::class => static fn () => new DefaultErrorHandler(),
];
