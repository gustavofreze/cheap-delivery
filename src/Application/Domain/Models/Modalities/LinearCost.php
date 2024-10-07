<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Modalities;

use CheapDelivery\Application\Domain\Models\Cost;
use CheapDelivery\Application\Domain\Models\Distance;
use CheapDelivery\Application\Domain\Models\Weight;

final readonly class LinearCost implements CostModality
{
    public function __construct(private Cost $variableCost)
    {
    }

    public function calculate(Weight $weight, Distance $distance): ?Cost
    {
        $product = $distance->multiply(multiplier: $weight);
        $cost = $product->multiply(multiplier: $this->variableCost);

        return new Cost(value: $cost->toFloat());
    }
}
