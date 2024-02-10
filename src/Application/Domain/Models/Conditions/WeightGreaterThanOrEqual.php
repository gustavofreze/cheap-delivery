<?php

namespace CheapDelivery\Application\Domain\Models\Conditions;

use CheapDelivery\Application\Domain\Models\Weight;

final readonly class WeightGreaterThanOrEqual implements CostCondition
{
    public function __construct(private Weight $threshold)
    {
    }

    public function apply(Weight $weight): bool
    {
        return $weight->isGreaterThanOrEqual(other: $this->threshold);
    }
}
