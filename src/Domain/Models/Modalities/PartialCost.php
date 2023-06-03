<?php

declare(strict_types=1);

namespace CheapDelivery\Domain\Models\Modalities;

use CheapDelivery\Domain\Models\Cost;
use CheapDelivery\Domain\Models\Distance;
use CheapDelivery\Domain\Models\Weight;
use CheapDelivery\Domain\Models\Conditions\CostCondition;

final class PartialCost implements CostModality
{
    public function __construct(private readonly CostModality $modality, private readonly CostCondition $condition)
    {
    }

    public function calculate(Weight $weight, Distance $distance): ?Cost
    {
        if (!$this->condition->apply(weight: $weight, distance: $distance)) {
            return null;
        }

        return $this->modality->calculate(weight: $weight, distance: $distance);
    }
}
