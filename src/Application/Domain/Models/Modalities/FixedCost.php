<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Modalities;

use CheapDelivery\Application\Domain\Models\Cost;
use CheapDelivery\Application\Domain\Models\Distance;
use CheapDelivery\Application\Domain\Models\Weight;

final readonly class FixedCost implements CostModality
{
    public function __construct(private Cost $fixedCost)
    {
    }

    public function calculate(Weight $weight, Distance $distance): ?Cost
    {
        return $this->fixedCost;
    }
}
