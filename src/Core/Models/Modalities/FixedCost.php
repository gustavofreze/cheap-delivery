<?php

declare(strict_types=1);

namespace CheapDelivery\Core\Models\Modalities;

use CheapDelivery\Core\Models\Cost;
use CheapDelivery\Core\Models\Distance;
use CheapDelivery\Core\Models\Weight;

final class FixedCost implements CostModality
{
    public function __construct(private Cost $fixedCost)
    {
    }

    public function calculate(Weight $weight, Distance $distance): ?Cost
    {
        return $this->fixedCost;
    }
}
