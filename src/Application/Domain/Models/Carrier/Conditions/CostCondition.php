<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Carrier\Conditions;

use CheapDelivery\Application\Domain\Models\Commons\Weight;

/**
 * Weight range a carrier modality is priced for.
 */
interface CostCondition
{
    /**
     * Answers whether the modality prices a shipment of this weight.
     *
     * @param Weight $weight The weight being shipped.
     * @return bool True when the modality applies, false otherwise.
     */
    public function apply(Weight $weight): bool;
}
