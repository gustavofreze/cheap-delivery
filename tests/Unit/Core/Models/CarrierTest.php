<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Core\Models;

use CheapDelivery\Core\Models\Carrier;
use CheapDelivery\Core\Models\Cost;
use CheapDelivery\Core\Models\Distance;
use CheapDelivery\Core\Models\Modalities\FixedCost;
use CheapDelivery\Core\Models\Name;
use CheapDelivery\Core\Models\Weight;
use PHPUnit\Framework\TestCase;

class CarrierTest extends TestCase
{
    public function testCalculateShippingCost(): void
    {
        $carrier = new Carrier(new Name('DHL'), new FixedCost(new Cost(150.00)));
        $shipment = $carrier->shipment(new Weight(100.00), new Distance(1200.00));

        self::assertEquals(150.00, $shipment->getCost()->getValue());
    }
}
