<?php

declare(strict_types=1);

namespace CheapDelivery\Query\Dispatch\FindAll\Database;

use CheapDelivery\Query\Dispatch\FindAll\DispatchesFinding;
use CheapDelivery\Query\Dispatch\Shared\ReadModel\Dispatch;
use Doctrine\DBAL\Connection;
use TinyBlocks\HttpQuery\Cursor\Keyset;
use TinyBlocks\HttpQuery\Cursor\Page;

final readonly class DispatchesFindingAdapter implements DispatchesFinding
{
    public function __construct(private Connection $connection)
    {
    }

    public function findAll(Keyset $keyset, array $comparisons): Page
    {
        $query = DispatchesKeysetQuery::from(keyset: $keyset, comparisons: $comparisons);

        $rows = $this->connection
            ->executeQuery(sql: $query->sql, params: $query->parameters)
            ->fetchAllAssociative();

        return $keyset
            ->page(items: $rows)
            ->map(transformation: static function (array $row): array {
                return Dispatch::from(
                    id: (string)$row['id'],
                    cost: (float)$row['cost'],
                    createdAt: (string)$row['created_at'],
                    carrierName: (string)$row['carrier_name']
                )->toArray();
            });
    }
}
