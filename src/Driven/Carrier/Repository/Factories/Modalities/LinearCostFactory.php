<?php

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Application\Domain\Models\Cost;
use CheapDelivery\Application\Domain\Models\Modalities\CostModality;
use CheapDelivery\Application\Domain\Models\Modalities\LinearCost;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\WrongModality;

final readonly class LinearCostFactory implements CostModalityFactory
{
    public function __construct(private array $costModality)
    {
        $modality = $this->costModality['modality'];

        if ($modality !== self::LINEAR) {
            throw new WrongModality(invalid: $modality, expected: self::LINEAR);
        }
    }

    public function build(): CostModality
    {
        return new LinearCost(variableCost: new Cost(value: (float)$this->costModality['cost']));
    }
}
