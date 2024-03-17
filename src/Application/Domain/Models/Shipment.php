<?php

namespace CheapDelivery\Application\Domain\Models;

readonly final class Shipment
{
    private function __construct(public Cost $cost, public Name $carrierName)
    {
    }

    public static function from(Cost $cost, Name $carrierName): Shipment
    {
        return new Shipment(cost: $cost, carrierName: $carrierName);
    }
}
