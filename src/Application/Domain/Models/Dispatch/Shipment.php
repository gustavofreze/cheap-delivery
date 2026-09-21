<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Dispatch;

use CheapDelivery\Application\Domain\Models\Commons\Cost;
use CheapDelivery\Application\Domain\Models\Commons\Name;
use CheapDelivery\Application\Domain\Models\Commons\ValueObject;
use CheapDelivery\Application\Domain\Models\Commons\ValueObjectBehavior;

final readonly class Shipment implements ValueObject
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
