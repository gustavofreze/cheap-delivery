<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Core\Models\Cost;
use CheapDelivery\Core\Models\Modalities\CostModality;
use CheapDelivery\Core\Models\Modalities\FixedCost;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\WrongModality;
use MongoDB\Model\BSONDocument;

final class FixedCostFactory implements CostModalityFactory
{
    public function __construct(private BSONDocument $costModality)
    {
        $modality = $this->costModality->modality;

        if ($modality !== self::FIXED) {
            throw new WrongModality(invalid: $modality, expected: self::FIXED);
        }
    }

    public function build(): CostModality
    {
        return new FixedCost(new Cost(floatval($this->costModality->cost)));
    }
}
