<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\WrongModality;
use PHPUnit\Framework\TestCase;

class PartialCostFactoryTest extends TestCase
{
    public function testExceptionWhenWrongModality(): void
    {
        /** @Given that I have the data of a modality not allowed for partial cost */
        $costModality = ['modality' => CostModalityFactory::LINEAR];

        /** @Then an exception indicating wrong modality should be thrown */
        $template = 'Invalid <%s> modality. Modality should be <%s>.';
        $this->expectException(WrongModality::class);
        $this->expectExceptionMessage(sprintf($template, $costModality['modality'], CostModalityFactory::PARTIAL));

        /** @When I try to build this modality */
        (new PartialCostFactory(costModality: $costModality))->build();
    }
}
