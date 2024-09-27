<?php

namespace CheapDelivery\Driven\Carrier\Repository;

use CheapDelivery\Application\Domain\Models\Carriers as CarriersCollection;
use CheapDelivery\Application\Ports\Outbound\Carriers;
use CheapDelivery\Driven\Shared\Database\RelationalConnection;
use TinyBlocks\Collection\Collectible;

final readonly class Adapter implements Carriers
{
    public function __construct(private RelationalConnection $connection)
    {
    }

    public function findAll(): CarriersCollection|Collectible
    {
        $result = $this->connection
            ->with()
            ->query(sql: Queries::FIND_ALL)
            ->execute()
            ->map(callback: fn(array $record) => Record::from(value: $record)->toCarrier());

        return CarriersCollection::createFrom(elements: $result);
    }
}
