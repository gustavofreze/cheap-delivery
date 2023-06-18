<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models\Conditions;

use CheapDelivery\Domain\Models\Distance;
use CheapDelivery\Domain\Models\Weight;

final class WeightGreaterThanOrEqual implements CostCondition
{
    public function __construct(private readonly Weight $threshold)
    {
    }

    public function apply(Weight $weight, Distance $distance): bool
    {
        return $weight->greaterThanOrEqual(weight: $this->threshold);
    }
}