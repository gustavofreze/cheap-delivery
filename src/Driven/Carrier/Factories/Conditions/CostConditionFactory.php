<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Conditions;

use CheapDelivery\Core\Models\Conditions\CostCondition;

interface CostConditionFactory
{
    public const WEIGHT_SMALLER_THAN = 'WeightSmallerThan';
    public const WEIGHT_GREATER_THAN_OR_EQUAL = 'WeightGreaterThanOrEqual';

    public function build(): CostCondition;
}
