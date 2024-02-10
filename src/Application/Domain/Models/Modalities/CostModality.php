<?php

namespace CheapDelivery\Application\Domain\Models\Modalities;

use CheapDelivery\Application\Domain\Models\Cost;
use CheapDelivery\Application\Domain\Models\Distance;
use CheapDelivery\Application\Domain\Models\Weight;

interface CostModality
{
    /**
     * Calculate the shipping cost based on weight and distance.
     *
     * @param Weight $weight The weight of the shipment.
     * @param Distance $distance The distance of the shipment.
     * @return Cost|null The calculated shipping cost or null if the cost cannot be determined.
     */
    public function calculate(Weight $weight, Distance $distance): ?Cost;
}
