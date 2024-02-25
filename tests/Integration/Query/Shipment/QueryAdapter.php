<?php

namespace Test\Integration\Query\Shipment;

use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;

final readonly class QueryAdapter
{
    private const INSERT_SHIPMENT = '
            INSERT INTO shipment (id, cost, carrier_name, created_at)
            VALUES (UUID_TO_BIN(:id), :cost, :carrierName, :createdAt);';

    protected Connection $connection;

    public function __construct(ContainerInterface $container)
    {
        $this->connection = $container->get(Connection::class);
        $this->connection->beginTransaction();
    }

    public function saveShipments(array $shipments): void
    {
        foreach ($shipments as $shipment) {
            $this->connection->executeStatement(self::INSERT_SHIPMENT, [
                'id'          => $shipment['id'],
                'cost'        => $shipment['cost'],
                'createdAt'   => $shipment['createdAt'],
                'carrierName' => $shipment['carrier']['name']
            ]);
        }
    }

    public function rollBack(): void
    {
        $this->connection->rollBack();
    }
}
