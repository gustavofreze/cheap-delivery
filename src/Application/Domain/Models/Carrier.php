<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Models\Modalities\CostModality;
use TinyBlocks\Vo\ValueObject;
use TinyBlocks\Vo\ValueObjectBehavior;

final readonly class Carrier implements ValueObject
{
    use ValueObjectBehavior;

    public function __construct(private Name $name, private CostModality $costModality)
    {
    }

    public function shipment(Weight $weight, Distance $distance): ?Shipment
    {
        $cost = $this->costModality->calculate(weight: $weight, distance: $distance);

        return $cost ? Shipment::from(cost: $cost, carrierName: $this->name) : null;
    }
}
