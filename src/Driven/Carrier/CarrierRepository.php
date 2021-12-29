<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier;

use CheapDelivery\Core\Repository\Carriers;
use CheapDelivery\Driven\Carrier\Factories\CarriersFactory;
use CheapDelivery\Driven\Database\NoSqlDatabase;

final class CarrierRepository implements Carriers
{
    private const CARRIER = 'carrier';

    public function __construct(private NoSqlDatabase $database)
    {
    }

    public function findAll(): array
    {
        return CarriersFactory::build($this->database->find(self::CARRIER));
    }
}
