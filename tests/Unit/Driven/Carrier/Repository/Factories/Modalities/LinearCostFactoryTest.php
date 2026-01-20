<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\WrongModality;
use PHPUnit\Framework\TestCase;

class LinearCostFactoryTest extends TestCase
{
    public function testExceptionWhenWrongModality(): void
    {
        /** @Given that I have the data of a modality not allowed for linear cost */
        $costModality = ['modality' => CostModalityFactory::PARTIAL];

        /** @Then an exception indicating wrong modality should be thrown */
        $template = 'Invalid <%s> modality. Modality should be <%s>.';
        $this->expectException(WrongModality::class);
        $this->expectExceptionMessage(sprintf($template, $costModality['modality'], CostModalityFactory::LINEAR));

        /** @When I try to build this modality */
        new LinearCostFactory(costModality: $costModality)->build();
    }
}
