<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Carrier\Conditions;

use CheapDelivery\Application\Domain\Models\Commons\Weight;

final readonly class WeightSmallerThan implements CostCondition
{
    private function __construct(private Weight $threshold)
    {
    }

    public static function from(Weight $threshold): WeightSmallerThan
    {
        return new WeightSmallerThan(threshold: $threshold);
    }

    public function apply(Weight $weight): bool
    {
        return $weight->isLessThan(other: $this->threshold);
    }
}
