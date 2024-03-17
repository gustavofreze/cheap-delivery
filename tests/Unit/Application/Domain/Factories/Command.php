<?php

namespace  CheapDelivery\Application\Domain\Factories;

use CheapDelivery\Application\Commands\DispatchWithLowestCost;
use CheapDelivery\Application\Domain\Models\Distance;
use CheapDelivery\Application\Domain\Models\Name;
use CheapDelivery\Application\Domain\Models\Person;
use CheapDelivery\Application\Domain\Models\Product;
use CheapDelivery\Application\Domain\Models\Weight;

final class Command
{
    public static function fromCalculateShipment(): DispatchWithLowestCost
    {
        $person = new Person(name: new Name(''), distance: new Distance(value: 1));
        $product = new Product(name: new Name(''), weight: new Weight(value: 1));

        return new DispatchWithLowestCost(person: $person, product: $product);
    }
}
