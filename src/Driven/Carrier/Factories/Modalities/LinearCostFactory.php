<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Domain\Models\Cost;
use CheapDelivery\Domain\Models\Modalities\CostModality;
use CheapDelivery\Domain\Models\Modalities\LinearCost;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\WrongModality;
use MongoDB\Model\BSONDocument;

final class LinearCostFactory implements CostModalityFactory
{
    public function __construct(private readonly BSONDocument $costModality)
    {
        $modality = $this->costModality->modality;

        if ($modality !== self::LINEAR) {
            throw new WrongModality(invalid: $modality, expected: self::LINEAR);
        }
    }

    public function build(): CostModality
    {
        return new LinearCost(variableCost: new Cost(value: floatval($this->costModality->cost)));
    }
}
