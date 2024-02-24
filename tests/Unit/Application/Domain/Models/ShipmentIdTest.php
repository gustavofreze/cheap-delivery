<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Models\Commons\Uuid;
use PHPUnit\Framework\TestCase;

class ShipmentIdTest extends TestCase
{
    public function testConstructorWithNullValue(): void
    {
        $shipmentId = new ShipmentId();

        self::assertInstanceOf(Uuid::class, $shipmentId->value);
        self::assertNotEmpty($shipmentId->value->toString());
    }

    public function testConstructorWithValue(): void
    {
        $uuid = Uuid::generateV4();
        $shipmentId = new ShipmentId(value: $uuid);

        self::assertSame($uuid, $shipmentId->value);
    }
}
