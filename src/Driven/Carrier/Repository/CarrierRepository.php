<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository;

use CheapDelivery\Application\Domain\Models\Carrier\Carrier;
use CheapDelivery\Application\Domain\Models\Carrier\Carriers as RegisteredCarriers;
use CheapDelivery\Application\Ports\Outbound\Carriers;
use CheapDelivery\Driven\Carrier\Repository\Records\CarrierRecordReader;
use CheapDelivery\Driven\Shared\Database\RelationalConnection;

final readonly class CarrierRepository implements Carriers
{
    public function __construct(private CarrierRecordReader $reader, private RelationalConnection $connection)
    {
    }

    public function findAll(): RegisteredCarriers
    {
        $records = $this->connection->fetchAll(sql: Queries::FIND_ALL);

        $carriers = array_map(
            fn(array $record): Carrier => $this->reader->toCarrier(record: $record),
            $records
        );

        return RegisteredCarriers::createFrom(elements: $carriers);
    }
}
