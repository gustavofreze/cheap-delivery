<?php

namespace CheapDelivery\Application\Domain\Models\Conditions;

use CheapDelivery\Application\Domain\Models\Weight;

interface CostCondition
{
    /**
     * Apply the condition to the given weight.
     *
     * @param Weight $weight The weight to which the condition is applied.
     * @return bool Whether the condition is satisfied for the given weight.
     */
    public function apply(Weight $weight): bool;
}
