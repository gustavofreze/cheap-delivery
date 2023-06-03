<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

use LogicException;
use PHPUnit\Framework\TestCase;

class ShipmentTest extends TestCase
{
    public function testCreationOfShipmentWithValidValues(): void
    {
        $data = ['carrier' => 'FedEx', 'cost' => 50.00];
        $shipment = new Shipment(cost: new Cost(value: $data['cost']), carrier: new Name(value: $data['carrier']));

        self::assertEquals(50.00, $shipment->getCost()->getValue());
        self::assertEquals('FedEx', $shipment->getCarrier()->getValue());
        self::assertEquals($data, $shipment->toArray());
    }

    /**
     * @dataProvider invalidShipment
     */
    public function testCreationOfShipmentWithInvalidValues(float $cost, string $name, string $message): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage($message);

        new Shipment(cost: new Cost(value: $cost), carrier: new Name(value: $name));
    }

    public function invalidShipment(): array
    {
        return [
            [
                'cost'    => 0.00,
                'name'    => 'Notebook',
                'message' => 'Cost cannot be zero or negative'
            ],
            [
                'cost'    => 50.00,
                'name'    => '',
                'message' => 'Name cannot be empty'
            ]
        ];
    }
}
