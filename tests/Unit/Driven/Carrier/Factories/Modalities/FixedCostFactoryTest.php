<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Core\Models\Modalities\FixedCost;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\WrongModality;
use CheapDelivery\Driven\Carrier\Factories\Modalities\CostModalityFactory;
use CheapDelivery\Driven\Carrier\Factories\Modalities\FixedCostFactory;
use MongoDB\Model\BSONDocument;
use PHPUnit\Framework\TestCase;

class FixedCostFactoryTest extends TestCase
{
    public function testWhenFixedCostModalityIsBuilt(): void
    {
        $payload = ['cost' => 10.00, 'modality' => CostModalityFactory::FIXED];
        $factory = new FixedCostFactory(new BSONDocument($payload));
        $costModality = $factory->build();

        self::assertInstanceOf(FixedCost::class, $costModality);
    }

    public function testWhenTryingToBuildDifferentModality(): void
    {
        $this->expectException(WrongModality::class);
        $this->expectErrorMessage('Invalid Linear modality. Modality should be Fixed');

        $payload = ['modality' => CostModalityFactory::LINEAR];
        $factory = new FixedCostFactory(new BSONDocument($payload));
        $factory->build();
    }
}
