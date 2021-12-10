<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Core\Models\Modalities\CostModality;
use CheapDelivery\Core\Models\Modalities\PartialCost;
use CheapDelivery\Driven\Carrier\Factories\Conditions\CostConditionGenericFactory;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\WrongModality;
use MongoDB\Model\BSONDocument;

final class PartialCostFactory implements CostModalityFactory
{
    public function __construct(private BSONDocument $costModality)
    {
        $modality = $this->costModality->modality;

        if ($modality !== self::PARTIAL) {
            throw new WrongModality(invalid: $modality, expected: self::PARTIAL);
        }
    }

    public function build(): CostModality
    {
        return new PartialCost(
            modality: (new CostModalityGenericFactory($this->costModality->costModality))->build(),
            condition: (new CostConditionGenericFactory($this->costModality->costCondition))->build()
        );
    }
}
