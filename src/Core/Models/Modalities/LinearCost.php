<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models\Modalities;

use CheapDelivery\Core\Models\Cost;
use CheapDelivery\Core\Models\Distance;
use CheapDelivery\Core\Models\Weight;

final class LinearCost implements CostModality
{
    public function __construct(private Cost $variableCost)
    {
    }

    public function calculate(Weight $weight, Distance $distance): ?Cost
    {
        $product = $distance->getValue() * $weight->getValue();
        $cost = $product * $this->variableCost->getValue();

        return new Cost($cost);
    }
}
