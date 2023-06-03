<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

use CheapDelivery\Domain\Models\Conditions\WeightGreaterThanOrEqual;
use CheapDelivery\Domain\Models\Conditions\WeightSmallerThan;
use CheapDelivery\Domain\Models\Modalities\CompositeCost;
use CheapDelivery\Domain\Models\Modalities\FixedCost;
use CheapDelivery\Domain\Models\Modalities\LinearCost;
use CheapDelivery\Domain\Models\Modalities\PartialCost;
use PHPUnit\Framework\TestCase;

class ShipmentsTest extends TestCase
{
    /**
     * @dataProvider weightAndDistance
     */
    public function testCalculateTheLowestShippingCost(Weight $weight, Distance $distance, Shipment $expected): void
    {
        $shipments = Shipments::from(carriers: $this->carriers(), weight: $weight, distance: $distance);
        $shipment = $shipments->lowestCost();

        self::assertEquals($expected->toArray(), $shipment->toArray());
        self::assertEquals($expected->getCost()->getValue(), $shipment->getCost()->getValue());
        self::assertEquals($expected->getCarrier()->getValue(), $shipment->getCarrier()->getValue());
    }

    public function weightAndDistance(): array
    {
        return [
            [
                'weight'   => new Weight(value: 1.00),
                'distance' => new Distance(value: 1.00),
                'expected' => new Shipment(cost: new Cost(value: 2.22), carrier: new Name(value: 'Loggi'))
            ],
            [
                'weight'   => new Weight(value: 4.00),
                'distance' => new Distance(value: 1000.00),
                'expected' => new Shipment(cost: new Cost(value: 210.00), carrier: new Name(value: 'DHL'))
            ],
            [
                'weight'   => new Weight(value: 9.50),
                'distance' => new Distance(value: 5.00),
                'expected' => new Shipment(cost: new Cost(value: 10.00), carrier: new Name(value: 'FedEx'))
            ]
        ];
    }

    private function carriers(): array
    {
        return [
            new Carrier(
                name: new Name(value: "DHL"),
                costModality: new CompositeCost(
                    modalityOne: new FixedCost(fixedCost: new Cost(value: 10.0)),
                    modalityTwo: new LinearCost(variableCost: new Cost(value: 0.05))
                )
            ),
            new Carrier(
                name: new Name(value: "FedEx"),
                costModality: new CompositeCost(
                    modalityOne: new FixedCost(fixedCost: new Cost(value: 4.30)),
                    modalityTwo: new LinearCost(variableCost: new Cost(value: 0.12))
                )
            ),
            new Carrier(
                name: new Name(value: "Loggi"),
                costModality: new CompositeCost(
                    modalityOne: new PartialCost(
                        modality: new CompositeCost(
                            modalityOne: new FixedCost(fixedCost: new Cost(value: 2.10)),
                            modalityTwo: new LinearCost(variableCost: new Cost(value: 0.12))
                        ),
                        condition: new WeightSmallerThan(threshold: new Weight(value: 5.0))
                    ),
                    modalityTwo: new PartialCost(
                        modality: new CompositeCost(
                            modalityOne: new FixedCost(fixedCost: new Cost(value: 10.0)),
                            modalityTwo: new LinearCost(variableCost: new Cost(value: 0.01))
                        ),
                        condition: new WeightGreaterThanOrEqual(threshold: new Weight(value: 5.0))
                    )
                )
            )
        ];
    }
}
