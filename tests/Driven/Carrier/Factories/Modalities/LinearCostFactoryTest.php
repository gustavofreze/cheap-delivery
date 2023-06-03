<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Domain\Models\Modalities\LinearCost;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\WrongModality;
use MongoDB\Model\BSONDocument;
use PHPUnit\Framework\TestCase;

class LinearCostFactoryTest extends TestCase
{
    public function testWhenLinearCostModalityIsBuilt(): void
    {
        $payload = ['cost' => 10.00, 'modality' => CostModalityFactory::LINEAR];
        $factory = new LinearCostFactory(costModality: new BSONDocument($payload));
        $costModality = $factory->build();

        self::assertInstanceOf(LinearCost::class, $costModality);
    }

    public function testWhenTryingToBuildDifferentModality(): void
    {
        $this->expectException(WrongModality::class);
        $this->expectExceptionMessage('Invalid Partial modality. Modality should be Linear');

        $payload = ['modality' => CostModalityFactory::PARTIAL];
        $factory = new LinearCostFactory(costModality: new BSONDocument($payload));
        $factory->build();
    }
}
