<?php

namespace CheapDelivery\Driven\Carrier\Repository\Factories\Modalities;

use CheapDelivery\Driven\Carrier\Repository\Factories\Exceptions\UnknownModality;
use PHPUnit\Framework\TestCase;

class CostModalityGenericFactoryTest extends TestCase
{
    public function testExceptionWhenUnknownModality(): void
    {
        /** @Given that I have an unmapped modality */
        $costModality = ['modality' => 'UNKNOWN'];

        /** @Then an error indicating unknown modality should occur */
        $this->expectException(UnknownModality::class);
        $this->expectExceptionMessage('Unknown <UNKNOWN> modality.');

        /** @When I try to build this modality */
        (new CostModalityGenericFactory(costModality: $costModality))->build();
    }
}
