<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\WrongModality;
use PHPUnit\Framework\TestCase;

class CompositeCostFactoryTest extends TestCase
{
    public function testExceptionWhenWrongModality(): void
    {
        /** @Given that I have the data of a modality not allowed for composite cost */
        $costModality = ['modality' => CostModalityFactory::FIXED];

        /** @Then an exception indicating wrong modality should be thrown */
        $template = 'Invalid <%s> modality. Modality should be <%s>.';
        $this->expectException(WrongModality::class);
        $this->expectExceptionMessage(sprintf($template, $costModality['modality'], CostModalityFactory::COMPOSITE));

        /** @When I try to build this modality */
        new CompositeCostFactory(costModality: $costModality)->build();
    }
}
