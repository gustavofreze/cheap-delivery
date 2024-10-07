<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Application\Domain\Models\Modalities\CostModality;
use CheapDelivery\Application\Domain\Models\Modalities\PartialCost;
use CheapDelivery\Driven\Carrier\Repository\Factories\Conditions\CostConditionGenericFactory;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\WrongModality;

final readonly class PartialCostFactory implements CostModalityFactory
{
    public function __construct(private array $costModality)
    {
        $modality = $this->costModality['modality'];

        if ($modality !== self::PARTIAL) {
            throw new WrongModality(invalid: $modality, expected: self::PARTIAL);
        }
    }

    public function build(): CostModality
    {
        return new PartialCost(
            modality: (new CostModalityGenericFactory(costModality: $this->costModality['costModality']))->build(),
            condition: (new CostConditionGenericFactory(costCondition: $this->costModality['costCondition']))->build()
        );
    }
}
