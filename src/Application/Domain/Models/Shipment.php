<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models;

use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

readonly final class Shipment implements ValueObject
{
    use ValueObjectBehavior;

    private function __construct(public Cost $cost, public Name $carrierName)
    {
    }

    public static function from(Cost $cost, Name $carrierName): Shipment
    {
        return new Shipment(cost: $cost, carrierName: $carrierName);
    }
}
