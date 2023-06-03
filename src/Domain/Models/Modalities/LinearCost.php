<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models\Modalities;

use CheapDelivery\Domain\Models\Cost;
use CheapDelivery\Domain\Models\Distance;
use CheapDelivery\Domain\Models\Weight;

final class LinearCost implements CostModality
{
    public function __construct(private readonly Cost $variableCost)
    {
    }

    public function calculate(Weight $weight, Distance $distance): ?Cost
    {
        $product = $distance->getValue() * $weight->getValue();
        $cost = $product * $this->variableCost->getValue();

        return new Cost(value: $cost);
    }
}
