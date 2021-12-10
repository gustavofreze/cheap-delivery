<?php

declare(strict_types=1);

namespace CheapDelivery\Unit\Driven\Carrier\Factories\Modalities;

use CheapDelivery\Driven\Carrier\Factories\Exceptions\UnknownModality;
use CheapDelivery\Driven\Carrier\Factories\Modalities\CostModalityGenericFactory;
use MongoDB\Model\BSONDocument;
use PHPUnit\Framework\TestCase;

class CostModalityGenericFactoryTest extends TestCase
{
    public function testUnknownModality(): void
    {
        $this->expectException(UnknownModality::class);
        $this->expectExceptionMessage('Unknown Xpto modality');

        $payload = ['modality' => 'Xpto'];

        (new CostModalityGenericFactory(new BSONDocument($payload)))->build();
    }
}
