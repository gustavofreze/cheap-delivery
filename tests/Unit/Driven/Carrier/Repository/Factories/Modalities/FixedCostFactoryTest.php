<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\WrongModality;
use PHPUnit\Framework\TestCase;

class FixedCostFactoryTest extends TestCase
{
    public function testExceptionWhenWrongModality(): void
    {
        /** @Given that I have the data of a modality not allowed for fixed cost */
        $costModality = ['modality' => CostModalityFactory::COMPOSITE];

        /** @Then an exception indicating wrong modality should be thrown */
        $template = 'Invalid <%s> modality. Modality should be <%s>.';
        $this->expectException(WrongModality::class);
        $this->expectExceptionMessage(sprintf($template, $costModality['modality'], CostModalityFactory::FIXED));

        /** @When I try to build this modality */
        (new FixedCostFactory(costModality: $costModality))->build();
    }
}
