<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Domain\Models\Modalities\FixedCost;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\WrongModality;
use MongoDB\Model\BSONDocument;
use PHPUnit\Framework\TestCase;

class FixedCostFactoryTest extends TestCase
{
    public function testWhenFixedCostModalityIsBuilt(): void
    {
        $payload = ['cost' => 10.00, 'modality' => CostModalityFactory::FIXED];
        $factory = new FixedCostFactory(costModality: new BSONDocument($payload));
        $costModality = $factory->build();

        self::assertInstanceOf(FixedCost::class, $costModality);
    }

    public function testWhenTryingToBuildDifferentModality(): void
    {
        $this->expectException(WrongModality::class);
        $this->expectExceptionMessage('Invalid Linear modality. Modality should be Fixed');

        $payload = ['modality' => CostModalityFactory::LINEAR];
        $factory = new FixedCostFactory(costModality: new BSONDocument($payload));
        $factory->build();
    }
}
