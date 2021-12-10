<?php

declare(strict_types=1);

namespace CheapDelivery\Driven\Carrier\Factories;

use CheapDelivery\Core\Models\Carrier;
use CheapDelivery\Core\Models\Name;
use CheapDelivery\Driven\Carrier\Factories\Modalities\CostModalityGenericFactory;
use MongoDB\Model\BSONDocument;

final class CarriersFactory
{
    public static function build(array $collection): array
    {
        return array_map(fn(BSONDocument $result) => new Carrier(
            name: new Name($result->name),
            costModality: (new CostModalityGenericFactory($result->costModality))->build()
        ), $collection);
    }
}
