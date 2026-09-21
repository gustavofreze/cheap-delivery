<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Carrier\Modalities;

use CheapDelivery\Application\Domain\Models\Commons\Cost;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Weight;

final readonly class LinearCost implements CostModality
{
    private function __construct(private Cost $rate)
    {
    }

    public static function from(Cost $rate): LinearCost
    {
        return new LinearCost(rate: $rate);
    }

    public function calculate(Weight $weight, Distance $distance): Cost
    {
        $travelled = $distance->toDecimal()->multipliedBy(multiplier: $weight->toDecimal());

        return Cost::from(value: $travelled->multipliedBy(multiplier: $this->rate->toDecimal())->toFloat());
    }
}
