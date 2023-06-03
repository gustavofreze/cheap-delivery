<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models;

use CheapDelivery\Domain\Models\Modalities\CostModality;

final class Carrier
{
    public function __construct(private readonly Name $name, private readonly CostModality $costModality)
    {
    }

    public function shipment(Weight $weight, Distance $distance): ?Shipment
    {
        $cost = $this->costModality->calculate(weight: $weight, distance: $distance);

        return $cost ? new Shipment(cost: $cost, carrier: $this->name) : null;
    }
}
