<?php

namespace CheapDelivery\Driven\Shared\Database\MySql;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Result;
use Doctrine\DBAL\Statement;
use CheapDelivery\Driven\Shared\Database\QueryBuilder;
use Throwable;

final class MySqlQueryBuilder implements QueryBuilder
{
    private Result $result;
    private Statement $statement;

    public function __construct(private readonly Connection $connection)
    {
    }

    public function map(callable $callback): array
    {
        try {
            return array_map($callback, $this->fetchAll());
        } catch (Throwable $exception) {
            throw new MySqlFailure(error: 'Mapping results', exception: $exception);
        }
    }

    public function bind(array $data): MySqlQueryBuilder
    {
        try {
            foreach ($data as $column => $value) {
                $this->statement->bindValue($column, $value);
            }
            return $this;
        } catch (Throwable $exception) {
            throw new MySqlFailure(error: 'Binding values', exception: $exception);
        }
    }

    public function query(string $sql): MySqlQueryBuilder
    {
        try {
            $this->statement = $this->connection->prepare($sql);
            return $this;
        } catch (Throwable $exception) {
            throw new MySqlFailure(error: 'Preparing query', exception: $exception);
        }
    }

    public function execute(): MySqlQueryBuilder
    {
        try {
            $this->result = $this->statement->executeQuery();
            return $this;
        } catch (Throwable $exception) {
            throw new MySqlFailure(error: 'Executing query', exception: $exception);
        }
    }

    public function fetchAll(): array
    {
        try {
            return $this->result->fetchAllAssociative();
        } catch (Throwable $exception) {
            throw new MySqlFailure(error: 'Fetching results', exception: $exception);
        }
    }
}
