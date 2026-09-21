<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Carrier\Modalities;

use CheapDelivery\Application\Domain\Models\Commons\Cost;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Weight;

final readonly class FixedCost implements CostModality
{
    private function __construct(private Cost $cost)
    {
    }

    public static function from(Cost $cost): FixedCost
    {
        return new FixedCost(cost: $cost);
    }

    public function calculate(Weight $weight, Distance $distance): Cost
    {
        return $this->cost;
    }
}
