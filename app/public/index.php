<?php

declare(strict_types=1);

use App\Http\Response\EmptyResponse;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Factory\StreamFactory;
use Slim\Psr7\Factory\UploadedFileFactory;
use Spiral\RoadRunner\Http\PSR7Worker;
use Spiral\RoadRunner\Worker;

use function App\env;

http_response_code(500);

require __DIR__ . '/../vendor/autoload.php';

if ($dsn = env('SENTRY_DSN')) {
    Sentry\init(['dsn' => $dsn]);
}

$psr7 = new PSR7Worker(
    Worker::create(),
    new ServerRequestFactory(),
    new StreamFactory(),
    new UploadedFileFactory(),
);

$config = require __DIR__ . '/../config/dependencies.php';

$createContainer = require __DIR__ . '/../config/container.php';
$createApp = require __DIR__ . '/../config/app.php';

while (true) {
    try {
        $request = $psr7->waitRequest();
        if ($request === null) {
            break;
        }
    } catch (Throwable $e) {
        $psr7->respond(new EmptyResponse(500));
        Sentry\captureException($e);
        continue;
    }

    try {
        $app = $createApp($createContainer($config));
        $response = $app->handle($request);
        $psr7->respond($response);
    } catch (Throwable $e) {
        $psr7->respond(new EmptyResponse(500));
        $psr7->getWorker()->error((string)$e);
        Sentry\captureException($e);
    }
}
