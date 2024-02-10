<?php

namespace CheapDelivery\Application\Domain\Models\Modalities;

use CheapDelivery\Application\Domain\Models\Conditions\CostCondition;
use CheapDelivery\Application\Domain\Models\Cost;
use CheapDelivery\Application\Domain\Models\Distance;
use CheapDelivery\Application\Domain\Models\Weight;

final readonly class PartialCost implements CostModality
{
    public function __construct(private CostModality $modality, private CostCondition $condition)
    {
    }

    public function calculate(Weight $weight, Distance $distance): ?Cost
    {
        if (!$this->condition->apply(weight: $weight)) {
            return null;
        }

        return $this->modality->calculate(weight: $weight, distance: $distance);
    }
}
