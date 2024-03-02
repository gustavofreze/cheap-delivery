<?php

namespace CheapDelivery\Driven\Shared\Database\MySql;

use Closure;
use Doctrine\DBAL\Connection;
use CheapDelivery\Driven\Shared\Database\RelationalConnection;
use Throwable;

final class MySqlEngine implements RelationalConnection
{
    private MySqlQueryBuilder $queryBuilder;

    public function __construct(private readonly Connection $connection)
    {
        $this->queryBuilder = new MySqlQueryBuilder(connection: $this->connection);
    }

    public function with(): MySqlQueryBuilder
    {
        return $this->queryBuilder;
    }

    public function inTransaction(Closure $useCase): void
    {
        try {
            $this->connection->beginTransaction();
            $useCase($this);
            $this->connection->commit();
        } catch (Throwable $exception) {
            $this->connection->rollBack();
            throw new MySqlFailure(error: 'Execute a set of operations within a transaction', exception: $exception);
        }
    }
}
