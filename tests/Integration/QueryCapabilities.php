<?php

namespace Test\Integration;

use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;

abstract class QueryCapabilities
{
    protected Connection $connection;

    public function __construct(ContainerInterface $container)
    {
        $this->connection = $container->get(Connection::class);
        $this->connection->beginTransaction();
    }

    public function rollBack(): void
    {
        $this->connection->rollBack();
    }
}
