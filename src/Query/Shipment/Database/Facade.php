<?php

namespace CheapDelivery\Query\Shipment\Database;

use Doctrine\DBAL\Connection;

final readonly class Facade
{
    public function __construct(private Connection $connection)
    {
    }

    public function findAll(): array
    {
        $shipments = $this->connection
            ->executeQuery(Queries::FIND_ALL)
            ->fetchAllAssociative();

        return Builder::from(shipments: $shipments);
    }
}
