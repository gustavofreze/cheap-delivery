<?php

namespace CheapDelivery\Driven\Carrier\Repository;

use CheapDelivery\Application\Domain\Models\Carriers;
use CheapDelivery\Application\Ports\Outbound\CarriersRepository;
use CheapDelivery\Driven\Shared\Database\RelationalConnection;

final readonly class Adapter implements CarriersRepository
{
    public function __construct(private RelationalConnection $connection)
    {
    }

    public function findAll(): Carriers
    {
        $result = $this->connection
            ->with()
            ->query(sql: Queries::FIND_ALL)
            ->execute()
            ->map(fn(array $record) => Record::from(value: $record)->toCarrier());

        return new Carriers(items: $result);
    }
}
