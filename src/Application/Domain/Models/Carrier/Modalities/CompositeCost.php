<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Carrier\Modalities;

use CheapDelivery\Application\Domain\Models\Commons\Cost;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Weight;

final readonly class CompositeCost implements CostModality
{
    private function __construct(private CostModality $first, private CostModality $second)
    {
    }

    public static function from(CostModality $first, CostModality $second): CompositeCost
    {
        return new CompositeCost(first: $first, second: $second);
    }

    public function calculate(Weight $weight, Distance $distance): ?Cost
    {
        $firstCost = $this->first->calculate(weight: $weight, distance: $distance);
        $secondCost = $this->second->calculate(weight: $weight, distance: $distance);

        if (is_null($firstCost)) {
            return $secondCost;
        }

        if (is_null($secondCost)) {
            return $firstCost;
        }

        return $firstCost->plus(addend: $secondCost);
    }
}
