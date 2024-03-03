<?php

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Application\Domain\Models\Cost;
use CheapDelivery\Application\Domain\Models\Modalities\CostModality;
use CheapDelivery\Application\Domain\Models\Modalities\FixedCost;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\WrongModality;

final readonly class FixedCostFactory implements CostModalityFactory
{
    public function __construct(private array $costModality)
    {
        $modality = $this->costModality['modality'];

        if ($modality !== self::FIXED) {
            throw new WrongModality(invalid: $modality, expected: self::FIXED);
        }
    }

    public function build(): CostModality
    {
        return new FixedCost(fixedCost: new Cost(value: (float)$this->costModality['cost']));
    }
}
