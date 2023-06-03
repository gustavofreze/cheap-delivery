<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

use CheapDelivery\Domain\Models\Modalities\FixedCost;
use PHPUnit\Framework\TestCase;

class CarrierTest extends TestCase
{
    public function testCalculateShippingCost(): void
    {
        $name = new Name(value: 'DHL');
        $costModality = new FixedCost(fixedCost: new Cost(value: 150.00));
        $carrier = new Carrier(name: $name, costModality: $costModality);
        $shipment = $carrier->shipment(weight: new Weight(value: 100.00), distance: new Distance(value: 1200.00));

        self::assertEquals(150.00, $shipment->getCost()->getValue());
    }
}
