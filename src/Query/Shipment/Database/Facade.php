<?php

namespace CheapDelivery\Query\Shipment\Database;

use CheapDelivery\Query\QueryBuilder;

final readonly class Facade
{
    public function __construct(private QueryBuilder $queryBuilder)
    {
    }

    public function findAll(ShipmentFilters $filters): array
    {
        $shipments = $this->queryBuilder
            ->select([
                'cost',
                'BIN_TO_UUID(id) AS id',
                'created_at      AS createdAt',
                'carrier_name    AS carrierName'
            ])
            ->from('shipment')
            ->applyFilters($filters)
            ->executeQuery()
            ->fetchAllAssociative();

        return Builder::from(shipments: $shipments);
    }
}
