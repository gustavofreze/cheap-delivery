<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Database;

interface NoSqlDatabase
{
    public function find(string $collectionName, array $filter = [], array $options = []): array;
}
