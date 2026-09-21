<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Carrier\Conditions;

use CheapDelivery\Application\Domain\Models\Commons\Weight;

final readonly class WeightGreaterThanOrEqual implements CostCondition
{
    private function __construct(private Weight $threshold)
    {
    }

    public static function from(Weight $threshold): WeightGreaterThanOrEqual
    {
        return new WeightGreaterThanOrEqual(threshold: $threshold);
    }

    public function apply(Weight $weight): bool
    {
        return $weight->isGreaterThanOrEqual(other: $this->threshold);
    }
}
