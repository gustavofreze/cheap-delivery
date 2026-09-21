<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Carrier\Modalities;

use CheapDelivery\Application\Domain\Models\Carrier\Conditions\CostCondition;
use CheapDelivery\Application\Domain\Models\Commons\Cost;
use CheapDelivery\Application\Domain\Models\Commons\Distance;
use CheapDelivery\Application\Domain\Models\Commons\Weight;

final readonly class PartialCost implements CostModality
{
    private function __construct(private CostModality $modality, private CostCondition $condition)
    {
    }

    public static function from(CostModality $modality, CostCondition $condition): PartialCost
    {
        return new PartialCost(modality: $modality, condition: $condition);
    }

    public function calculate(Weight $weight, Distance $distance): ?Cost
    {
        if (!$this->condition->apply(weight: $weight)) {
            return null;
        }

        return $this->modality->calculate(weight: $weight, distance: $distance);
    }
}
