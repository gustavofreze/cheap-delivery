<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Carrier\Modalities;

use CheapDelivery\Application\Domain\Models\Commons\Cost;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Weight;

/**
 * How a carrier prices a shipment of a given weight over a given distance.
 */
interface CostModality
{
    /**
     * Calculates what the carrier charges for the shipment.
     *
     * @param Weight $weight The weight being shipped.
     * @param Distance $distance The distance the shipment travels.
     * @return Cost|null The price, or null when this modality does not apply to the shipment.
     */
    public function calculate(Weight $weight, Distance $distance): ?Cost;
}
