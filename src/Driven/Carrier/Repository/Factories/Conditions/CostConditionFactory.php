<?php

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Conditions;

use CheapDelivery\Application\Domain\Models\Conditions\CostCondition;

interface CostConditionFactory
{
    public const WEIGHT_SMALLER_THAN = 'WeightSmallerThan';
    public const WEIGHT_GREATER_THAN_OR_EQUAL = 'WeightGreaterThanOrEqual';

    /**
     * Builds a CostCondition instance based on the factory type.
     *
     * @return CostCondition The created CostCondition instance.
     */
    public function build(): CostCondition;
}
