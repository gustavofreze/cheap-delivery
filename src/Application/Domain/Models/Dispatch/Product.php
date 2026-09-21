<?php

declare(strict_types=1);

namespace CheapDelivery\Application\Domain\Models\Dispatch;

use CheapDelivery\Application\Domain\Models\Commons\Name;
use CheapDelivery\Application\Domain\Models\Commons\ValueObject;
use CheapDelivery\Application\Domain\Models\Commons\ValueObjectBehavior;
use CheapDelivery\Application\Domain\Models\Commons\Weight;

final readonly class Product implements ValueObject
{
    use ValueObjectBehavior;

    private function __construct(public Name $name, public Weight $weight)
    {
    }

    public static function from(Name $name, Weight $weight): Product
    {
        return new Product(name: $name, weight: $weight);
    }
}
