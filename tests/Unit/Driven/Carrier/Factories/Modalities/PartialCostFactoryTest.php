<?php

namespace CheapDelivery\Unit\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Core\Models\Modalities\PartialCost;
use CheapDelivery\Driven\Carrier\Factories\Conditions\CostConditionFactory;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\WrongModality;
use CheapDelivery\Driven\Carrier\Factories\Modalities\CostModalityFactory;
use CheapDelivery\Driven\Carrier\Factories\Modalities\PartialCostFactory;
use MongoDB\Model\BSONDocument;
use PHPUnit\Framework\TestCase;

class PartialCostFactoryTest extends TestCase
{
    public function testWhenPartialCostModalityIsBuilt(): void
    {
        $payload = [
            'modality' => CostModalityFactory::PARTIAL,
            'costModality' => new BSONDocument([
                'modality' => CostModalityFactory::COMPOSITE,
                'modalityOne' => new BSONDocument(['cost' => 2.10, 'modality' => CostModalityFactory::FIXED]),
                'modalityTwo' => new BSONDocument(['cost' => 0.12, 'modality' => CostModalityFactory::LINEAR])
            ]),
            'costCondition' => new BSONDocument(['name' => CostConditionFactory::WEIGHT_SMALLER_THAN, 'weight' => 5.00])
        ];
        $factory = new PartialCostFactory(new BSONDocument($payload));
        $costModality = $factory->build();

        self::assertInstanceOf(PartialCost::class, $costModality);
    }

    public function testWhenTryingToBuildDifferentModality(): void
    {
        $this->expectException(WrongModality::class);
        $this->expectErrorMessage('Invalid Composite modality. Modality should be Partial');

        $payload = ['modality' => CostModalityFactory::COMPOSITE];
        $factory = new PartialCostFactory(new BSONDocument($payload));
        $factory->build();
    }
}
