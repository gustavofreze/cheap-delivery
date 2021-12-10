<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Core\Models;

use CheapDelivery\Core\Models\Carrier;
use CheapDelivery\Core\Models\Conditions\WeightGreaterThanOrEqual;
use CheapDelivery\Core\Models\Conditions\WeightSmallerThan;
use CheapDelivery\Core\Models\Cost;
use CheapDelivery\Core\Models\Distance;
use CheapDelivery\Core\Models\Modalities\CompositeCost;
use CheapDelivery\Core\Models\Modalities\FixedCost;
use CheapDelivery\Core\Models\Modalities\LinearCost;
use CheapDelivery\Core\Models\Modalities\PartialCost;
use CheapDelivery\Core\Models\Name;
use CheapDelivery\Core\Models\Shipment;
use CheapDelivery\Core\Models\Shipments;
use CheapDelivery\Core\Models\Weight;
use PHPUnit\Framework\TestCase;

class ShipmentsTest extends TestCase
{
    /**
     * @param Weight $weight
     * @param Distance $distance
     * @param Shipment $expected
     * @return void
     * @dataProvider weightAndDistance
     */
    public function testCalculateTheLowestShippingCost(Weight $weight, Distance $distance, Shipment $expected): void
    {
        $shipments = Shipments::from($this->carriers(), $weight, $distance);
        $shipment = $shipments->lowestCost();

        self::assertEquals($expected->toArray(), $shipment->toArray());
        self::assertEquals($expected->getCost()->getValue(), $shipment->getCost()->getValue());
        self::assertEquals($expected->getCarrier()->getValue(), $shipment->getCarrier()->getValue());
    }

    public function weightAndDistance(): array
    {
        return [
            [
                'weight' => new Weight(1.00),
                'distance' => new Distance(1.00),
                'expected' => new Shipment(new Cost(2.22), new Name('Loggi'))
            ],
            [
                'weight' => new Weight(4.00),
                'distance' => new Distance(1000.00),
                'expected' => new Shipment(new Cost(210.00), new Name('DHL'))
            ],
            [
                'weight' => new Weight(9.50),
                'distance' => new Distance(5.00),
                'expected' => new Shipment(new Cost(10.00), new Name('FedEx'))
            ]
        ];
    }

    private function carriers(): array
    {
        return [
            new Carrier(
                name: new Name("DHL"),
                costModality: new CompositeCost(
                    modalityOne: new FixedCost(new Cost(10.0)),
                    modalityTwo: new LinearCost(new Cost(0.05))
                )
            ),
            new Carrier(
                name: new Name("FedEx"),
                costModality: new CompositeCost(
                    modalityOne: new FixedCost(new Cost(4.30)),
                    modalityTwo: new LinearCost(new Cost(0.12))
                )
            ),
            new Carrier(
                name: new Name("Loggi"),
                costModality: new CompositeCost(
                    modalityOne: new PartialCost(
                        modality: new CompositeCost(
                            modalityOne: new FixedCost(new Cost(2.10)),
                            modalityTwo: new LinearCost(new Cost(0.12))
                        ),
                        condition: new WeightSmallerThan(new Weight(5.0))
                    ),
                    modalityTwo: new PartialCost(
                        modality: new CompositeCost(
                            modalityOne: new FixedCost(new Cost(10.0)),
                            modalityTwo: new LinearCost(new Cost(0.01))
                        ),
                        condition: new WeightGreaterThanOrEqual(new Weight(5.0))
                    )
                )
            )
        ];
    }
}
