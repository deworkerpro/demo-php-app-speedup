<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

use function App\env;

$cacheConfig = [
    'config_cache_path' => __DIR__ . '/../var/cache/config-cache.php',
];

$aggregator = new ConfigAggregator([
    new PhpFileProvider(__DIR__ . '/common/*.php'),
    new ArrayProvider($cacheConfig),
    new PhpFileProvider(__DIR__ . '/' . env('APP_ENV', 'prod') . '/*.php'),
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();
