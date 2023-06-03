<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Database;

use CheapDelivery\Driven\Database\Mongo\MongoAdapter;

final class NoSqlDatabaseEngine implements NoSqlDatabase
{
    public function __construct(private readonly MongoAdapter $adapter)
    {
    }

    public function find(string $collectionName, array $filter = [], array $options = []): array
    {
        return $this->adapter
            ->collection(collectionName: $collectionName)
            ->find(filter: $filter, options: $options);
    }
}
