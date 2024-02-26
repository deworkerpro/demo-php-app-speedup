<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use Psr\Container\ContainerInterface;

return static function (array $config): ContainerInterface {
    $builder = new ContainerBuilder();

    $builder->addDefinitions($config);

    return $builder->build();
};
