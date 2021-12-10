<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Core\Models\Modalities\CompositeCost;
use CheapDelivery\Core\Models\Modalities\CostModality;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\WrongModality;
use MongoDB\Model\BSONDocument;

final class CompositeCostFactory implements CostModalityFactory
{
    public function __construct(private BSONDocument $costModality)
    {
        $modality = $this->costModality->modality;

        if ($modality !== self::COMPOSITE) {
            throw new WrongModality(invalid: $modality, expected: self::COMPOSITE);
        }
    }

    public function build(): CostModality
    {
        return new CompositeCost(
            modalityOne: (new CostModalityGenericFactory($this->costModality->modalityOne))->build(),
            modalityTwo: (new CostModalityGenericFactory($this->costModality->modalityTwo))->build()
        );
    }
}
