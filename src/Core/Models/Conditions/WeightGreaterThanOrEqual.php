<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models\Conditions;

use CheapDelivery\Core\Models\Distance;
use CheapDelivery\Core\Models\Weight;

final class WeightGreaterThanOrEqual implements CostCondition
{
    public function __construct(private Weight $threshold)
    {
    }

    public function apply(Weight $weight, Distance $distance): bool
    {
        return $weight->greaterThanOrEqual($this->threshold);
    }
}
