<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier;

use CheapDelivery\Domain\Ports\Outbound\Carriers;
use CheapDelivery\Driven\Carrier\Factories\CarriersFactory;
use CheapDelivery\Driven\Database\NoSqlDatabase;

final class CarrierRepository implements Carriers
{
    private const CARRIER = 'carrier';

    public function __construct(private readonly NoSqlDatabase $database)
    {
    }

    public function findAll(): array
    {
        return CarriersFactory::build(collection: $this->database->find(collectionName: self::CARRIER));
    }
}
