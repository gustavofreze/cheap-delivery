<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Domain\Models\Modalities\CompositeCost;
use CheapDelivery\Domain\Models\Modalities\CostModality;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\WrongModality;
use MongoDB\Model\BSONDocument;

final class CompositeCostFactory implements CostModalityFactory
{
    public function __construct(private readonly BSONDocument $costModality)
    {
        $modality = $this->costModality->modality;

        if ($modality !== self::COMPOSITE) {
            throw new WrongModality(invalid: $modality, expected: self::COMPOSITE);
        }
    }

    public function build(): CostModality
    {
        return new CompositeCost(
            modalityOne: (new CostModalityGenericFactory(costModality: $this->costModality->modalityOne))->build(),
            modalityTwo: (new CostModalityGenericFactory(costModality: $this->costModality->modalityTwo))->build()
        );
    }
}
