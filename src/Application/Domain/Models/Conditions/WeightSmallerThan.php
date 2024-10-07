<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Conditions;

use CheapDelivery\Application\Domain\Models\Weight;

final readonly class WeightSmallerThan implements CostCondition
{
    public function __construct(private Weight $threshold)
    {
    }

    public function apply(Weight $weight): bool
    {
        return $weight->isLessThan(other: $this->threshold);
    }
}
