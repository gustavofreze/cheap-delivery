<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Core\Models;

use CheapDelivery\Core\Models\Cost;
use CheapDelivery\Core\Models\Name;
use CheapDelivery\Core\Models\Shipment;
use LogicException;
use PHPUnit\Framework\TestCase;

class ShipmentTest extends TestCase
{
    public function testCreationOfShipmentWithValidValues(): void
    {
        $data = ['carrier' => 'FedEx', 'cost' => 50.00];
        $shipment = new Shipment(new Cost($data['cost']), new Name($data['carrier']));

        self::assertEquals(50.00, $shipment->getCost()->getValue());
        self::assertEquals('FedEx', $shipment->getCarrier()->getValue());
        self::assertEquals($data, $shipment->toArray());
    }

    /**
     * @param float $cost
     * @param string $name
     * @param string $message
     * @return void
     * @dataProvider invalidShipment
     */
    public function testCreationOfShipmentWithInvalidValues(float $cost, string $name, string $message): void
    {
        $this->expectException(LogicException::class);
        $this->expectErrorMessage($message);

        new Shipment(new Cost($cost), new Name($name));
    }

    public function invalidShipment(): array
    {
        return [
            [
                'cost' => 0.00,
                'name' => 'Notebook',
                'message' => 'Cost cannot be zero or negative'
            ],
            [
                'cost' => 50.00,
                'name' => '',
                'message' => 'Name cannot be empty'
            ],
        ];
    }
}
