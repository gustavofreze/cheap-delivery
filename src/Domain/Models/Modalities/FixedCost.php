<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models\Modalities;

use CheapDelivery\Domain\Models\Cost;
use CheapDelivery\Domain\Models\Distance;
use CheapDelivery\Domain\Models\Weight;

final class FixedCost implements CostModality
{
    public function __construct(private readonly Cost $fixedCost)
    {
    }

    public function calculate(Weight $weight, Distance $distance): ?Cost
    {
        return $this->fixedCost;
    }
}
