<?php

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Application\Domain\Models\Modalities\CompositeCost;
use CheapDelivery\Application\Domain\Models\Modalities\CostModality;
use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\WrongModality;

final readonly class CompositeCostFactory implements CostModalityFactory
{
    public function __construct(private array $costModality)
    {
        $modality = $this->costModality['modality'];

        if ($modality !== self::COMPOSITE) {
            throw new WrongModality(invalid: $modality, expected: self::COMPOSITE);
        }
    }

    public function build(): CostModality
    {
        return new CompositeCost(
            modalityOne: (new CostModalityGenericFactory(costModality: $this->costModality['modalityOne']))->build(),
            modalityTwo: (new CostModalityGenericFactory(costModality: $this->costModality['modalityTwo']))->build()
        );
    }
}
