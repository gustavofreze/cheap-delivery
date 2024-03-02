<?php

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Conditions;

use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\UnknownCondition;
use PHPUnit\Framework\TestCase;

class CostConditionGenericFactoryTest extends TestCase
{
    public function testExceptionWhenUnknownCondition(): void
    {
        /** @Given that I have an unmapped cost condition */
        $costCondition = ['name' => 'UNKNOWN', 'weight' => rand(1, 100)];

        /** @Then an error indicating unknown condition should occur */
        $this->expectException(UnknownCondition::class);
        $this->expectExceptionMessage('Unknown <UNKNOWN> condition.');

        /** @When I try to build this condition */
        (new CostConditionGenericFactory(costCondition: $costCondition))->build();
    }
}
