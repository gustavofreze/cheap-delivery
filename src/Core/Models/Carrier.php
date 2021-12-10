<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models;

use CheapDelivery\Core\Models\Modalities\CostModality;

final class Carrier
{
    public function __construct(private Name $name, private CostModality $costModality)
    {
    }

    public function shipment(Weight $weight, Distance $distance): ?Shipment
    {
        $cost = $this->costModality->calculate($weight, $distance);

        return $cost ? new Shipment($cost, $this->name) : null;
    }
}
