<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Domain\Models\Modalities\CompositeCost;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\WrongModality;
use MongoDB\Model\BSONDocument;
use PHPUnit\Framework\TestCase;

class CompositeCostFactoryTest extends TestCase
{
    public function testWhenCompositeCostModalityIsBuilt(): void
    {
        $payload = [
            'modality'    => CostModalityFactory::COMPOSITE,
            'modalityOne' => new BSONDocument(['cost' => 10.00, 'modality' => CostModalityFactory::FIXED]),
            'modalityTwo' => new BSONDocument(['cost' => 0.05, 'modality' => CostModalityFactory::LINEAR])
        ];
        $factory = new CompositeCostFactory(costModality: new BSONDocument($payload));
        $costModality = $factory->build();

        self::assertInstanceOf(CompositeCost::class, $costModality);
    }

    public function testWhenTryingToBuildDifferentModality(): void
    {
        $this->expectException(WrongModality::class);
        $this->expectExceptionMessage('Invalid Fixed modality. Modality should be Composite');

        $payload = ['modality' => CostModalityFactory::FIXED];
        $factory = new CompositeCostFactory(costModality: new BSONDocument($payload));
        $factory->build();
    }
}
