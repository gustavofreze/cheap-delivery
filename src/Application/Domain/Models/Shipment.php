<?php

namespace CheapDelivery\Application\Domain\Models;

readonly final class Shipment
{
    private function __construct(public ShipmentId $id, public Cost $cost, public Name $carrierName)
    {
    }

    public static function from(Cost $cost, Name $carrierName): Shipment
    {
        return new Shipment(id: new ShipmentId(), cost: $cost, carrierName: $carrierName);
    }
}
