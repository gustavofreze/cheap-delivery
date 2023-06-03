<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Driven\Carrier\Factories\Exceptions\UnknownModality;
use MongoDB\Model\BSONDocument;
use PHPUnit\Framework\TestCase;

class CostModalityGenericFactoryTest extends TestCase
{
    public function testUnknownModality(): void
    {
        $this->expectException(UnknownModality::class);
        $this->expectExceptionMessage('Unknown Xpto modality');

        $payload = ['modality' => 'Xpto'];

        (new CostModalityGenericFactory(costModality: new BSONDocument($payload)))->build();
    }
}
