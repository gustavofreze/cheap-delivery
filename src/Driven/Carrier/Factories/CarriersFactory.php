<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories;

use CheapDelivery\Domain\Models\Carrier;
use CheapDelivery\Domain\Models\Name;
use CheapDelivery\Driven\Carrier\Factories\Modalities\CostModalityGenericFactory;
use MongoDB\Model\BSONDocument;

final class CarriersFactory
{
    public static function build(array $collection): array
    {
        return array_map(fn(BSONDocument $result) => new Carrier(
            name: new Name(value: $result->name),
            costModality: (new CostModalityGenericFactory(costModality: $result->costModality))->build()
        ), $collection);
    }
}
