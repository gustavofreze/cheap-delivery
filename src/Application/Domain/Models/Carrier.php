<?php

namespace CheapDelivery\Application\Domain\Models;

use CheapDelivery\Application\Domain\Models\Modalities\CostModality;

final readonly class Carrier
{
    public function __construct(private Name $name, private CostModality $costModality)
    {
    }

    public function shipment(Weight $weight, Distance $distance): ?Shipment
    {
        $cost = $this->costModality->calculate(weight: $weight, distance: $distance);

        return $cost ? Shipment::from(cost: $cost, carrierName: $this->name) : null;
    }
}
