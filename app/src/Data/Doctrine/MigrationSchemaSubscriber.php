<?php

declare(strict_types=1);

namespace App\Data\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\ToolEvents;

final class MigrationSchemaSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            ToolEvents::postGenerateSchema => 'postGenerateSchema',
        ];
    }

    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schema = $args->getSchema();

        $table = $schema->createTable('migrations');
        $table->addColumn('version', 'string', ['notnull' => true, 'length' => 191]);
        $table->addColumn('executed_at', 'datetime', ['notnull' => false]);
        $table->addColumn('execution_time', 'integer', ['notnull' => false]);
        $table->setPrimaryKey(['version']);
    }
}
