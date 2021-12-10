<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Driven\Carrier\Factories\Conditions;

use CheapDelivery\Core\Models\Conditions\WeightGreaterThanOrEqual;
use CheapDelivery\Core\Models\Conditions\WeightSmallerThan;
use CheapDelivery\Driven\Carrier\Factories\Conditions\CostConditionFactory;
use CheapDelivery\Driven\Carrier\Factories\Conditions\CostConditionGenericFactory;
use CheapDelivery\Driven\Carrier\Factories\Exceptions\UnknownCondition;
use MongoDB\Model\BSONDocument;
use PHPUnit\Framework\TestCase;

class CostConditionGenericFactoryTest extends TestCase
{
    public function testWeightSmallerThan(): void
    {
        $payload = ['name' => CostConditionFactory::WEIGHT_SMALLER_THAN, 'weight' => 100.00];
        $factory = new CostConditionGenericFactory(new BSONDocument($payload));
        $costCondition = $factory->build();

        self::assertInstanceOf(WeightSmallerThan::class, $costCondition);
    }

    public function testWeightGreaterThanOrEqual(): void
    {
        $payload = ['name' => CostConditionFactory::WEIGHT_GREATER_THAN_OR_EQUAL, 'weight' => 99.99];
        $factory = new CostConditionGenericFactory(new BSONDocument($payload));
        $costCondition = $factory->build();

        self::assertInstanceOf(WeightGreaterThanOrEqual::class, $costCondition);
    }

    public function testUnknownCondition(): void
    {
        $this->expectException(UnknownCondition::class);
        $this->expectExceptionMessage('Unknown XptoEquals condition');

        $payload = ['name' => 'XptoEquals', 'weight' => 1.00];

        (new CostConditionGenericFactory(new BSONDocument($payload)))->build();
    }
}
